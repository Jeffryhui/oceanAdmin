# SqlImportUtils 使用文档

## 概述

`SqlImportUtils` 是一个强大的 SQL 文件导入工具类，支持小文件快速导入和大文件流式处理，具备完整的安全检查、错误处理和进度监控功能。

## 特性

- 🚀 **智能文件处理**：自动根据文件大小选择最优处理方式
- 📊 **大文件支持**：支持最大 2GB 的 SQL 文件流式导入
- 🛡️ **安全检查**：内置多层安全检查，防止危险 SQL 操作
- 💾 **内存优化**：流式处理避免内存溢出，支持内存使用监控
- ⚡ **批量执行**：可配置批量大小，提升执行效率
- 📈 **进度监控**：实时进度回调，支持进度条显示
- 🔄 **事务支持**：可选的事务回滚机制
- 🌍 **编码支持**：自动检测和转换文件编码（UTF-8、GBK、GB2312、BIG5）

## 快速开始

### 基本用法

```php
use App\Utils\SqlImportUtils;

try {
    // 快速导入
    $result = SqlImportUtils::quickImport('/path/to/database.sql');
    
    echo "导入完成: 成功 {$result['success']} 条，失败 {$result['failed']} 条\n";
    
} catch (Exception $e) {
    echo "导入失败: " . $e->getMessage() . "\n";
}
```

### 大文件导入

```php
// 大文件导入（带进度显示）
$result = SqlImportUtils::importLargeFile('/path/to/large_database.sql', 
    function($progress, $lineNumber, $statementNumber) {
        echo "\r进度: " . number_format($progress, 1) . "% - 第 {$lineNumber} 行 - {$statementNumber} 条SQL";
    }
);

echo "\n导入完成！\n";
```

## API 参考

### 主要方法

#### `importSqlFile(string $filePath, array $options = []): array`

导入 SQL 文件的主方法，自动选择处理方式。

**参数：**
- `$filePath`: SQL 文件路径
- `$options`: 配置选项数组

**返回值：**
```php
[
    'total' => 100,           // 总SQL语句数
    'success' => 95,          // 成功执行数
    'failed' => 5,            // 失败数量
    'errors' => [...],        // 错误详情
    'execution_time' => 1500, // 执行时间(毫秒)
    'memory_peak' => '128MB', // 内存峰值（大文件模式）
    'file_size' => 1048576    // 文件大小（大文件模式）
]
```

#### `quickImport(string $filePath, bool $useTransaction = true, bool $stopOnError = false): array`

快速导入方法，使用默认安全配置。

#### `importLargeFile(string $filePath, ?callable $progressCallback = null, array $options = []): array`

大文件导入方法，强制使用流式处理。

#### `validateSqlFile(string $filePath): array`

验证 SQL 文件但不执行，用于预检查。

**返回值：**
```php
[
    'file_path' => '/path/to/file.sql',
    'file_size' => 1048576,
    'file_size_human' => '1.00 MB',
    'total_statements' => 100,
    'statements_preview' => ['CREATE TABLE...', 'INSERT INTO...', ...]
]
```

#### `getDatabaseInfo(): array`

获取当前数据库信息。

## 配置选项

### 基本选项

| 选项 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `use_transaction` | bool | `true` | 是否使用事务 |
| `stop_on_error` | bool | `false` | 遇到错误是否停止 |
| `rollback_on_error` | bool | `true` | 有错误时是否回滚 |
| `skip_security_check` | bool | `false` | 是否跳过安全检查 |
| `log_sql` | bool | `false` | 是否显示执行的SQL |

### 大文件专用选项

| 选项 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `large_file_threshold` | int | `10MB` | 大文件判断阈值 |
| `batch_size` | int | `100` | 批量执行大小 |
| `memory_limit` | int | `256MB` | 内存使用限制 |
| `progress_callback` | callable | `null` | 进度回调函数 |

## 使用示例

### 1. 基本导入

```php
use App\Utils\SqlImportUtils;

try {
    $result = SqlImportUtils::importSqlFile('/path/to/database.sql');
    
    echo "导入结果:\n";
    echo "总计: {$result['total']} 条\n";
    echo "成功: {$result['success']} 条\n";
    echo "失败: {$result['failed']} 条\n";
    echo "耗时: {$result['execution_time']} 毫秒\n";
    
    // 显示错误详情
    if (!empty($result['errors'])) {
        echo "\n错误详情:\n";
        foreach ($result['errors'] as $error) {
            echo "第{$error['index']}条SQL失败: {$error['error']}\n";
            echo "SQL: {$error['sql']}\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "导入失败: " . $e->getMessage() . "\n";
}
```

### 2. 自定义配置导入

```php
$result = SqlImportUtils::importSqlFile('/path/to/database.sql', [
    'use_transaction' => true,        // 使用事务
    'stop_on_error' => false,         // 遇到错误继续执行
    'rollback_on_error' => false,     // 不回滚，保留成功的部分
    'skip_security_check' => false,   // 进行安全检查
    'log_sql' => true,               // 显示执行的SQL
]);
```

### 3. 大文件导入（带详细进度）

```php
$startTime = time();

$result = SqlImportUtils::importLargeFile('/path/to/huge_database.sql', 
    function($progress, $lineNumber, $statementNumber) use ($startTime) {
        $elapsed = time() - $startTime;
        $eta = $progress > 0 ? ($elapsed / $progress * 100) - $elapsed : 0;
        
        printf("\r进度: %5.1f%% | 行: %d | SQL: %d | 用时: %ds | 预计: %ds     ", 
            $progress, $lineNumber, $statementNumber, $elapsed, $eta);
    },
    [
        'batch_size' => 200,                           // 批量执行200条
        'memory_limit' => 512 * 1024 * 1024,          // 512MB内存限制
        'stop_on_error' => false,                      // 遇到错误继续
        'rollback_on_error' => false,                  // 不回滚
    ]
);

echo "\n\n导入完成！\n";
echo "文件大小: " . number_format($result['file_size'] / 1024 / 1024, 2) . " MB\n";
echo "内存峰值: {$result['memory_peak']}\n";
echo "总耗时: " . number_format($result['execution_time'] / 1000, 2) . " 秒\n";
```

### 4. 预检查 SQL 文件

```php
try {
    $validation = SqlImportUtils::validateSqlFile('/path/to/database.sql');
    
    echo "文件检查结果:\n";
    echo "文件路径: {$validation['file_path']}\n";
    echo "文件大小: {$validation['file_size_human']}\n";
    echo "SQL语句数: {$validation['total_statements']}\n";
    
    echo "\n前3条语句预览:\n";
    foreach ($validation['statements_preview'] as $index => $sql) {
        echo ($index + 1) . ". " . substr($sql, 0, 100) . "...\n";
    }
    
    echo "\n✅ 文件验证通过，可以安全导入\n";
    
} catch (Exception $e) {
    echo "❌ 文件验证失败: " . $e->getMessage() . "\n";
}
```

### 5. 获取数据库信息

```php
try {
    $dbInfo = SqlImportUtils::getDatabaseInfo();
    
    echo "数据库信息:\n";
    echo "数据库名: {$dbInfo['database_name']}\n";
    echo "表数量: {$dbInfo['tables_count']}\n\n";
    
    echo "表详情:\n";
    foreach ($dbInfo['tables'] as $table) {
        printf("%-20s %10s 行 %10s\n", 
            $table['name'], 
            number_format($table['rows']), 
            $table['size']
        );
    }
    
} catch (Exception $e) {
    echo "获取数据库信息失败: " . $e->getMessage() . "\n";
}
```

### 6. 命令行工具示例

```php
#!/usr/bin/env php
<?php
// import_sql.php

require_once __DIR__ . '/vendor/autoload.php';

use App\Utils\SqlImportUtils;

if ($argc < 2) {
    echo "用法: php import_sql.php <sql_file_path> [options]\n";
    echo "选项:\n";
    echo "  --no-transaction  不使用事务\n";
    echo "  --stop-on-error   遇到错误停止\n";
    echo "  --batch-size=N    批量大小（默认100）\n";
    exit(1);
}

$filePath = $argv[1];
$options = [
    'use_transaction' => !in_array('--no-transaction', $argv),
    'stop_on_error' => in_array('--stop-on-error', $argv),
    'log_sql' => false,
];

// 解析批量大小
foreach ($argv as $arg) {
    if (strpos($arg, '--batch-size=') === 0) {
        $options['batch_size'] = (int)substr($arg, 13);
    }
}

try {
    echo "开始导入: {$filePath}\n";
    
    $result = SqlImportUtils::importLargeFile($filePath, 
        function($progress, $line, $statements) {
            echo "\r进度: " . str_pad(number_format($progress, 1), 5, ' ', STR_PAD_LEFT) . "% - {$statements} 条SQL";
        },
        $options
    );
    
    echo "\n\n🎉 导入完成！\n";
    echo "成功: {$result['success']} 条\n";
    echo "失败: {$result['failed']} 条\n";
    echo "耗时: " . number_format($result['execution_time'] / 1000, 2) . " 秒\n";
    
    if ($result['failed'] > 0) {
        echo "\n❌ 失败的SQL:\n";
        foreach (array_slice($result['errors'], 0, 5) as $error) {
            echo "- {$error['error']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ 导入失败: " . $e->getMessage() . "\n";
    exit(1);
}
```

## 安全特性

### 危险操作检测

工具会自动检测并阻止以下危险操作：

- 系统函数调用 (`system`, `exec`, `shell_exec` 等)
- 文件操作 (`load_file`, `into outfile` 等)
- 用户权限操作 (`grant`, `revoke` 等)
- 数据库删除 (`drop database`)
- 用户管理 (`create/drop/alter user`)
- 系统表修改

### 安全配置建议

```php
// 生产环境推荐配置
$safeOptions = [
    'use_transaction' => true,          // 使用事务
    'stop_on_error' => true,           // 遇到错误停止
    'rollback_on_error' => true,       // 错误时回滚
    'skip_security_check' => false,    // 不跳过安全检查
    'log_sql' => false,                // 不记录敏感SQL
];
```

## 错误处理

### 常见错误类型

1. **文件错误**
   - 文件不存在
   - 文件不可读
   - 文件过大
   - 文件格式不支持

2. **SQL 错误**
   - 语法错误
   - 表不存在
   - 字段不存在
   - 约束冲突

3. **安全错误**
   - 危险操作
   - 受限语句
   - 语句过长

4. **系统错误**
   - 内存不足
   - 连接超时
   - 磁盘空间不足

### 错误处理示例

```php
try {
    $result = SqlImportUtils::importSqlFile($filePath, [
        'stop_on_error' => false,  // 不停止，收集所有错误
    ]);
    
    if ($result['failed'] > 0) {
        echo "导入完成，但有 {$result['failed']} 条SQL失败:\n";
        
        foreach ($result['errors'] as $error) {
            echo "\n第 {$error['index']} 条SQL失败:\n";
            echo "错误: {$error['error']}\n";
            echo "SQL: {$error['sql']}\n";
            
            // 根据错误类型进行处理
            if (strpos($error['error'], 'Duplicate entry') !== false) {
                echo "提示: 数据重复，可能需要使用 INSERT IGNORE 或 ON DUPLICATE KEY UPDATE\n";
            } elseif (strpos($error['error'], "doesn't exist") !== false) {
                echo "提示: 表或字段不存在，请检查数据库结构\n";
            }
        }
    }
    
} catch (Exception $e) {
    $message = $e->getMessage();
    
    if (strpos($message, '文件不存在') !== false) {
        echo "请检查文件路径是否正确\n";
    } elseif (strpos($message, '内存使用超限') !== false) {
        echo "请增加内存限制或减小批量大小\n";
    } elseif (strpos($message, '危险操作') !== false) {
        echo "SQL包含危险操作，请检查文件内容\n";
    } else {
        echo "导入失败: {$message}\n";
    }
}
```

## 性能优化建议

### 1. 文件大小优化

```php
// 不同文件大小的推荐配置
$configs = [
    // 小文件 (< 10MB)
    'small' => [
        'use_transaction' => true,
        'batch_size' => 50,
    ],
    
    // 中等文件 (10MB - 100MB)
    'medium' => [
        'use_transaction' => true,
        'batch_size' => 200,
        'memory_limit' => 256 * 1024 * 1024,
    ],
    
    // 大文件 (> 100MB)
    'large' => [
        'use_transaction' => false,  // 关闭事务提升性能
        'batch_size' => 500,
        'memory_limit' => 512 * 1024 * 1024,
        'rollback_on_error' => false,
    ],
];
```

### 2. 数据库优化

```sql
-- 导入前优化设置
SET foreign_key_checks = 0;
SET unique_checks = 0;
SET autocommit = 0;

-- 导入完成后恢复
SET foreign_key_checks = 1;
SET unique_checks = 1;
SET autocommit = 1;
```

### 3. 系统资源优化

```php
// 增加PHP内存限制
ini_set('memory_limit', '1G');

// 增加执行时间
ini_set('max_execution_time', 0);

// 关闭输出缓冲
if (ob_get_level()) {
    ob_end_clean();
}
```

## 最佳实践

### 1. 导入前准备

```php
// 1. 验证文件
$validation = SqlImportUtils::validateSqlFile($filePath);
echo "将导入 {$validation['total_statements']} 条SQL语句\n";

// 2. 备份数据库
$backupFile = "backup_" . date('Y-m-d_H-i-s') . ".sql";
// 执行备份命令...

// 3. 检查磁盘空间
$freeSpace = disk_free_space('/');
$fileSize = $validation['file_size'];
if ($freeSpace < $fileSize * 2) {
    throw new Exception('磁盘空间不足');
}
```

### 2. 分步导入大文件

```php
// 将大文件分割成小文件导入
function importLargeFileInChunks($filePath, $chunkSize = 1000) {
    $statements = [];
    $chunkNumber = 0;
    
    // 读取并分割文件...
    
    foreach ($chunks as $chunk) {
        $chunkNumber++;
        echo "导入第 {$chunkNumber} 块...\n";
        
        $result = SqlImportUtils::importSqlFile($chunk, [
            'use_transaction' => true,
            'stop_on_error' => true,
        ]);
        
        echo "完成: {$result['success']} 条\n";
    }
}
```

### 3. 错误恢复

```php
// 记录导入进度，支持断点续传
function importWithResume($filePath, $resumeFrom = 0) {
    $progressFile = $filePath . '.progress';
    
    if (file_exists($progressFile) && $resumeFrom === 0) {
        $resumeFrom = (int)file_get_contents($progressFile);
        echo "从第 {$resumeFrom} 条SQL继续导入...\n";
    }
    
    $result = SqlImportUtils::importLargeFile($filePath, 
        function($progress, $line, $statements) use ($progressFile) {
            // 定期保存进度
            if ($statements % 100 === 0) {
                file_put_contents($progressFile, $statements);
            }
        }
    );
    
    // 导入完成，删除进度文件
    unlink($progressFile);
    
    return $result;
}
```

## 常见问题

### Q: 如何处理超大文件（> 1GB）？

A: 使用大文件模式并优化配置：

```php
$result = SqlImportUtils::importLargeFile($filePath, $progressCallback, [
    'batch_size' => 1000,              // 增加批量大小
    'memory_limit' => 1024 * 1024 * 1024, // 1GB内存
    'use_transaction' => false,         // 关闭事务
    'rollback_on_error' => false,       // 不回滚
]);
```

### Q: 导入时内存不足怎么办？

A: 调整内存相关配置：

```php
// 减小批量大小
'batch_size' => 50,

// 增加内存限制
'memory_limit' => 512 * 1024 * 1024,

// 或在系统级别增加PHP内存
ini_set('memory_limit', '2G');
```

### Q: 如何跳过重复数据？

A: 修改SQL语句使用 `INSERT IGNORE` 或 `ON DUPLICATE KEY UPDATE`：

```php
// 预处理SQL文件，替换INSERT为INSERT IGNORE
$content = file_get_contents($sqlFile);
$content = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $content);
file_put_contents($tempFile, $content);

$result = SqlImportUtils::importSqlFile($tempFile);
```

### Q: 如何监控导入进度？

A: 使用进度回调：

```php
$result = SqlImportUtils::importLargeFile($filePath, 
    function($progress, $lineNumber, $statementNumber) {
        // 输出到日志文件
        file_put_contents('import.log', 
            date('Y-m-d H:i:s') . " 进度: {$progress}%\n", 
            FILE_APPEND
        );
        
        // 或发送到监控系统
        // sendMetric('import.progress', $progress);
    }
);
```

### Q: 导入失败如何回滚？

A: 使用事务模式：

```php
$result = SqlImportUtils::importSqlFile($filePath, [
    'use_transaction' => true,      // 启用事务
    'rollback_on_error' => true,    // 有错误时回滚
    'stop_on_error' => true,        // 遇到错误停止
]);
```

## 更新日志

### v1.0.0
- 初始版本
- 支持小文件和大文件导入
- 内置安全检查
- 流式处理支持
- 进度监控功能
- 完整的错误处理

---

> 📝 **提示**: 更多使用案例和最新更新请参考项目源码中的示例文件。

> ⚠️ **注意**: 在生产环境中使用时，建议先在测试环境验证，并做好数据备份。 