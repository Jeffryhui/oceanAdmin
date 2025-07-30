<?php

namespace App\Utils;

use Hyperf\DbConnection\Db;
use Exception;

class SqlImportUtils
{
    /**
     * 导入SQL文件
     * @param string $filePath 文件路径
     * @param array $options 选项
     * @return array
     * @throws Exception
     */
    public static function importSqlFile(string $filePath, array $options = []): array
    {
        // 验证文件
        self::validateFile($filePath);
        
        $fileSize = filesize($filePath);
        $largeFileThreshold = $options['large_file_threshold'] ?? (10 * 1024 * 1024); // 10MB
        
        // 根据文件大小选择处理方式
        if ($fileSize > $largeFileThreshold) {
            return self::importLargeSqlFile($filePath, $options);
        } else {
            return self::importSmallSqlFile($filePath, $options);
        }
    }

    /**
     * 导入小文件（传统方式）
     * @param string $filePath
     * @param array $options
     * @return array
     */
    private static function importSmallSqlFile(string $filePath, array $options = []): array
    {
        // 读取文件内容
        $sqlContent = self::readFileContent($filePath);
        
        // 解析SQL语句
        $sqlStatements = self::parseSqlStatements($sqlContent);
        
        // 安全检查
        if (!($options['skip_security_check'] ?? false)) {
            self::securityCheck($sqlStatements);
        }
        
        // 执行SQL语句
        return self::executeSqlStatements($sqlStatements, $options);
    }

    /**
     * 导入大文件（流式处理）
     * @param string $filePath
     * @param array $options
     * @return array
     * @throws Exception
     */
    private static function importLargeSqlFile(string $filePath, array $options = []): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'execution_time' => 0,
            'memory_peak' => 0,
            'file_size' => filesize($filePath),
        ];

        $startTime = microtime(true);
        $useTransaction = $options['use_transaction'] ?? true;
        $stopOnError = $options['stop_on_error'] ?? false;
        $rollbackOnError = $options['rollback_on_error'] ?? true;
        $batchSize = $options['batch_size'] ?? 100; // 批量执行大小
        $progressCallback = $options['progress_callback'] ?? null;

        try {
            if ($useTransaction) {
                Db::beginTransaction();
            }

            $results = self::processLargeFileStream($filePath, $results, $options);

            if ($useTransaction) {
                if ($results['failed'] > 0 && $rollbackOnError) {
                    Db::rollBack();
                    throw new Exception('存在执行失败的SQL语句，已回滚所有操作');
                } else {
                    Db::commit();
                }
            }

        } catch (\Throwable $e) {
            if ($useTransaction) {
                Db::rollBack();
            }
            throw new Exception('SQL执行失败: ' . $e->getMessage());
        }

        $results['execution_time'] = round((microtime(true) - $startTime) * 1000, 2);
        $results['memory_peak'] = round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB';

        return $results;
    }

    /**
     * 流式处理大文件
     * @param string $filePath
     * @param array $results
     * @param array $options
     * @return array
     * @throws Exception
     */
    private static function processLargeFileStream(string $filePath, array $results, array $options): array
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new Exception("无法打开文件: {$filePath}");
        }

        $currentStatement = '';
        $inString = false;
        $stringChar = '';
        $inComment = false;
        $commentType = '';
        $lineNumber = 0;
        $statementNumber = 0;
        $batchSize = $options['batch_size'] ?? 100;
        $batchStatements = [];
        $progressCallback = $options['progress_callback'] ?? null;
        $fileSize = filesize($filePath);
        $processedBytes = 0;

        try {
            while (($line = fgets($handle)) !== false) {
                $lineNumber++;
                $processedBytes += strlen($line);
                
                // 内存检查
                if ($lineNumber % 1000 === 0) {
                    self::checkMemoryUsage($options);
                    
                    // 进度回调
                    if ($progressCallback && is_callable($progressCallback)) {
                        $progress = $fileSize > 0 ? ($processedBytes / $fileSize) * 100 : 0;
                        $progressCallback($progress, $lineNumber, $statementNumber);
                    }
                }

                $line = rtrim($line, "\r\n");
                
                // 处理每一行
                $parsedLine = self::parseLine($line, $inString, $stringChar, $inComment, $commentType);
                
                if ($parsedLine !== null) {
                    $currentStatement .= $parsedLine . ' ';
                    
                    // 检查是否语句结束
                    if (self::isStatementComplete($parsedLine, $inString, $inComment)) {
                        $statement = trim($currentStatement);
                        if (!empty($statement)) {
                            $statementNumber++;
                            $results['total']++;
                            
                            // 安全检查
                            if (!($options['skip_security_check'] ?? false)) {
                                self::securityCheckSingle($statement, $statementNumber);
                            }
                            
                            $batchStatements[] = $statement;
                            
                            // 批量执行
                            if (count($batchStatements) >= $batchSize) {
                                $results = self::executeBatch($batchStatements, $results, $options);
                                $batchStatements = [];
                            }
                        }
                        $currentStatement = '';
                    }
                }
            }
            
            // 处理最后一批和剩余语句
            if (!empty(trim($currentStatement))) {
                $statementNumber++;
                $results['total']++;
                
                if (!($options['skip_security_check'] ?? false)) {
                    self::securityCheckSingle(trim($currentStatement), $statementNumber);
                }
                
                $batchStatements[] = trim($currentStatement);
            }
            
            if (!empty($batchStatements)) {
                $results = self::executeBatch($batchStatements, $results, $options);
            }

        } finally {
            fclose($handle);
        }

        return $results;
    }

    /**
     * 解析单行内容
     * @param string $line
     * @param bool $inString
     * @param string $stringChar
     * @param bool $inComment
     * @param string $commentType
     * @return string|null
     */
    private static function parseLine(string $line, bool &$inString, string &$stringChar, bool &$inComment, string &$commentType): ?string
    {
        $result = '';
        $length = strlen($line);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $line[$i];
            $nextChar = $i + 1 < $length ? $line[$i + 1] : '';
            
            // 处理字符串
            if ($inString) {
                $result .= $char;
                if ($char === $stringChar && ($i === 0 || $line[$i-1] !== '\\')) {
                    $inString = false;
                }
                continue;
            }
            
            // 处理注释
            if ($inComment) {
                if ($commentType === 'multi' && $char === '*' && $nextChar === '/') {
                    $inComment = false;
                    $i++; // 跳过下一个字符
                }
                continue;
            }
            
            // 检查注释开始
            if ($char === '-' && $nextChar === '-') {
                break; // 单行注释，忽略行的剩余部分
            }
            
            if ($char === '/' && $nextChar === '*') {
                $inComment = true;
                $commentType = 'multi';
                $i++; // 跳过下一个字符
                continue;
            }
            
            if ($char === '#') {
                break; // MySQL注释，忽略行的剩余部分
            }
            
            // 检查字符串开始
            if ($char === '"' || $char === "'") {
                $inString = true;
                $stringChar = $char;
            }
            
            $result .= $char;
        }
        
        return trim($result) !== '' ? $result : null;
    }

    /**
     * 检查语句是否完整
     * @param string $line
     * @param bool $inString
     * @param bool $inComment
     * @return bool
     */
    private static function isStatementComplete(string $line, bool $inString, bool $inComment): bool
    {
        return !$inString && !$inComment && str_ends_with(trim($line), ';');
    }

    /**
     * 检查内存使用
     * @param array $options
     * @throws Exception
     */
    private static function checkMemoryUsage(array $options): void
    {
        $memoryLimit = $options['memory_limit'] ?? (256 * 1024 * 1024); // 256MB
        $currentUsage = memory_get_usage(true);
        
        if ($currentUsage > $memoryLimit) {
            throw new Exception("内存使用超限: " . round($currentUsage/1024/1024, 2) . "MB");
        }
    }

    /**
     * 单条SQL安全检查
     * @param string $statement
     * @param int $index
     * @throws Exception
     */
    private static function securityCheckSingle(string $statement, int $index): void
    {
        self::securityCheck([$statement]);
    }

    /**
     * 批量执行SQL语句
     * @param array $statements
     * @param array $results
     * @param array $options
     * @return array
     */
    private static function executeBatch(array $statements, array $results, array $options): array
    {
        foreach ($statements as $statement) {
            try {
                if ($options['log_sql'] ?? false) {
                    echo "执行SQL: " . substr($statement, 0, 100) . "...\n";
                }
                
                Db::statement($statement);
                $results['success']++;
                
            } catch (\Throwable $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'sql' => substr($statement, 0, 200) . (strlen($statement) > 200 ? '...' : ''),
                    'error' => $e->getMessage(),
                ];
                
                if ($options['stop_on_error'] ?? false) {
                    throw new Exception('SQL执行失败: ' . $e->getMessage());
                }
            }
        }
        
        return $results;
    }

    /**
     * 验证文件
     * @param string $filePath
     * @throws Exception
     */
    private static function validateFile(string $filePath): void
    {
        // 检查文件是否存在
        if (!file_exists($filePath)) {
            throw new Exception("文件不存在: {$filePath}");
        }

        // 检查是否为文件
        if (!is_file($filePath)) {
            throw new Exception("路径不是文件: {$filePath}");
        }

        // 检查文件是否可读
        if (!is_readable($filePath)) {
            throw new Exception("文件不可读: {$filePath}");
        }

        // 检查文件大小 (最大2GB)
        $maxSize = 2 * 1024 * 1024 * 1024; // 2GB
        if (filesize($filePath) > $maxSize) {
            throw new Exception('文件大小不能超过2GB');
        }

        // 检查文件扩展名
        $allowedExtensions = ['sql', 'txt'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception('只支持.sql或.txt文件');
        }
    }

    /**
     * 读取文件内容
     * @param string $filePath
     * @return string
     * @throws Exception
     */
    private static function readFileContent(string $filePath): string
    {
        $content = file_get_contents($filePath);
        
        if ($content === false) {
            throw new Exception("读取文件失败: {$filePath}");
        }

        if (empty($content)) {
            throw new Exception('文件内容为空');
        }

        // 检测编码并转换为UTF-8
        $encoding = mb_detect_encoding($content, ['UTF-8', 'GBK', 'GB2312', 'BIG5'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        return $content;
    }

    /**
     * 解析SQL语句
     * @param string $sqlContent
     * @return array
     */
    private static function parseSqlStatements(string $sqlContent): array
    {
        // 移除注释
        $sqlContent = self::removeComments($sqlContent);
        
        // 按分号分割SQL语句，处理存储过程等复杂情况
        $statements = self::splitSqlStatements($sqlContent);
        
        $parsedStatements = [];
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $parsedStatements[] = $statement;
            }
        }

        return $parsedStatements;
    }

    /**
     * 智能分割SQL语句
     * @param string $sql
     * @return array
     */
    private static function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $length = strlen($sql);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            
            // 处理字符串内容
            if ($inString) {
                $current .= $char;
                if ($char === $stringChar && ($i === 0 || $sql[$i-1] !== '\\')) {
                    $inString = false;
                }
                continue;
            }
            
            // 检查字符串开始
            if ($char === '"' || $char === "'") {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
                continue;
            }
            
            // 处理分号
            if ($char === ';') {
                $current .= $char;
                $statements[] = $current;
                $current = '';
                continue;
            }
            
            $current .= $char;
        }
        
        // 添加最后一条语句（如果没有分号结尾）
        if (!empty(trim($current))) {
            $statements[] = $current;
        }
        
        return $statements;
    }

    /**
     * 移除SQL注释
     * @param string $sql
     * @return string
     */
    private static function removeComments(string $sql): string
    {
        // 移除单行注释 --（但保留字符串内的）
        $sql = preg_replace('/(?:^|(?<=\s))--.*$/m', '', $sql);
        
        // 移除多行注释 /* */
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // 移除#注释
        $sql = preg_replace('/(?:^|(?<=\s))#.*$/m', '', $sql);
        
        return $sql;
    }

    /**
     * 安全检查
     * @param array $statements
     * @throws Exception
     */
    private static function securityCheck(array $statements): void
    {
        $dangerousPatterns = [
            // 系统相关函数
            '/\b(system|exec|shell_exec|passthru|`)\s*\(/i',
            
            // 文件操作
            '/\b(load_file|into\s+outfile|into\s+dumpfile)\b/i',
            
            // 用户权限操作
            '/\b(grant|revoke)\s+/i',
            
            // 危险函数
            '/\b(benchmark|sleep)\s*\(/i',
            
            // 信息收集
            '/\b(information_schema\.|mysql\.|performance_schema\.)/i',
            
            // 存储过程/函数（可选限制）
            // '/\b(create\s+(procedure|function))\b/i',
        ];

        $restrictedStatements = [
            // 删除数据库
            '/\bdrop\s+database\b/i',
            
            // 用户管理
            '/\b(drop|alter|create)\s+user\b/i',
            
            // 修改系统表
            '/\b(update|delete|insert\s+into)\s+mysql\./i',
        ];

        foreach ($statements as $index => $statement) {
            // 检查危险模式
            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $statement)) {
                    throw new Exception("第" . ($index + 1) . "条SQL语句包含危险操作: " . substr($statement, 0, 100) . "...");
                }
            }
            
            // 检查受限语句
            foreach ($restrictedStatements as $pattern) {
                if (preg_match($pattern, $statement)) {
                    throw new Exception("第" . ($index + 1) . "条SQL语句包含受限操作: " . substr($statement, 0, 100) . "...");
                }
            }
            
            // 检查语句长度
            if (strlen($statement) > 50000) {
                throw new Exception("第" . ($index + 1) . "条SQL语句过长，可能存在安全风险");
            }
        }
    }

    /**
     * 执行SQL语句
     * @param array $statements
     * @param array $options
     * @return array
     */
    private static function executeSqlStatements(array $statements, array $options = []): array
    {
        $results = [
            'total' => count($statements),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'execution_time' => 0,
        ];

        $startTime = microtime(true);
        $useTransaction = $options['use_transaction'] ?? true;
        $stopOnError = $options['stop_on_error'] ?? false;
        $rollbackOnError = $options['rollback_on_error'] ?? true;

        try {
            if ($useTransaction) {
                Db::beginTransaction();
            }

            foreach ($statements as $index => $statement) {
                try {
                    // 记录执行的SQL（可选）
                    if ($options['log_sql'] ?? false) {
                        echo "执行第" . ($index + 1) . "条SQL: " . substr($statement, 0, 100) . "...\n";
                    }
                    
                    Db::statement($statement);
                    $results['success']++;
                    
                } catch (\Throwable $e) {
                    $results['failed']++;
                    $error = [
                        'index' => $index + 1,
                        'sql' => substr($statement, 0, 200) . (strlen($statement) > 200 ? '...' : ''),
                        'error' => $e->getMessage(),
                    ];
                    $results['errors'][] = $error;
                    
                    // 如果设置了遇到错误就停止
                    if ($stopOnError) {
                        break;
                    }
                }
            }

            if ($useTransaction) {
                if ($results['failed'] > 0 && $rollbackOnError) {
                    Db::rollBack();
                    throw new Exception('存在执行失败的SQL语句，已回滚所有操作');
                } else {
                    Db::commit();
                }
            }

        } catch (\Throwable $e) {
            if ($useTransaction) {
                Db::rollBack();
            }
            throw new Exception('SQL执行失败: ' . $e->getMessage());
        }

        $results['execution_time'] = round((microtime(true) - $startTime) * 1000, 2); // 毫秒

        return $results;
    }

    /**
     * 验证SQL文件（不执行）
     * @param string $filePath
     * @return array
     */
    public static function validateSqlFile(string $filePath): array
    {
        self::validateFile($filePath);
        $sqlContent = self::readFileContent($filePath);
        $sqlStatements = self::parseSqlStatements($sqlContent);
        
        // 进行安全检查
        self::securityCheck($sqlStatements);
        
        return [
            'file_path' => $filePath,
            'file_size' => filesize($filePath),
            'file_size_human' => self::formatBytes(filesize($filePath)),
            'total_statements' => count($sqlStatements),
            'statements_preview' => array_slice($sqlStatements, 0, 3), // 前3条语句预览
        ];
    }

    /**
     * 获取数据库信息
     * @return array
     */
    public static function getDatabaseInfo(): array
    {
        try {
            $tables = Db::select("SHOW TABLES");
            $dbName = Db::select("SELECT DATABASE() as db_name")[0]->db_name;
            
            return [
                'database_name' => $dbName,
                'tables_count' => count($tables),
                'tables' => array_map(function($table) use ($dbName) {
                    $tableName = $table->{"Tables_in_$dbName"};
                    $tableInfo = Db::select("SHOW TABLE STATUS LIKE '$tableName'")[0] ?? null;
                    return [
                        'name' => $tableName,
                        'rows' => $tableInfo->Rows ?? 0,
                        'size' => self::formatBytes($tableInfo->Data_length ?? 0),
                    ];
                }, $tables)
            ];
        } catch (\Throwable $e) {
            throw new Exception('获取数据库信息失败: ' . $e->getMessage());
        }
    }

    /**
     * 格式化字节数
     * @param int $bytes
     * @return string
     */
    private static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * 快速导入（常用选项的简化方法）
     * @param string $filePath
     * @param bool $useTransaction
     * @param bool $stopOnError
     * @return array
     */
    public static function quickImport(string $filePath, bool $useTransaction = true, bool $stopOnError = false): array
    {
        return self::importSqlFile($filePath, [
            'use_transaction' => $useTransaction,
            'stop_on_error' => $stopOnError,
            'rollback_on_error' => true,
            'log_sql' => false,
        ]);
    }

    /**
     * 大文件导入（带进度回调）
     * @param string $filePath
     * @param callable|null $progressCallback
     * @param array $options
     * @return array
     */
    public static function importLargeFile(string $filePath, ?callable $progressCallback = null, array $options = []): array
    {
        $defaultOptions = [
            'use_transaction' => true,
            'stop_on_error' => false,
            'rollback_on_error' => true,
            'log_sql' => false,
            'batch_size' => 100,
            'memory_limit' => 512 * 1024 * 1024, // 512MB
            'large_file_threshold' => 0, // 强制使用大文件模式
            'progress_callback' => $progressCallback,
        ];

        return self::importSqlFile($filePath, array_merge($defaultOptions, $options));
    }
} 