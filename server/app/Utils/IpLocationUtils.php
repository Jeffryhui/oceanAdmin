<?php

declare(strict_types=1);

namespace App\Utils;

use Ip2Region;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class IpLocationUtils
{
    private LoggerInterface $logger;
    private string $dbPath;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(LoggerFactory::class)->get('ip_location');
        $this->dbPath = BASE_PATH . '/storage/ip2region.xdb';
    }

    /**
     * 获取IP地址所属地信息
     *
     * @param string $ip IP地址
     * @return array 返回地理位置信息数组
     */
    public function getLocation(string $ip): array
    {
        try {
            // 验证IP地址格式
            if (!$this->isValidIp($ip)) {
                return $this->getDefaultResult('无效的IP地址');
            }

            // 内网IP直接返回
            if ($this->isPrivateIp($ip)) {
                return $this->getDefaultResult('内网IP');
            }

            // 检查数据库文件是否存在
            if (!file_exists($this->dbPath)) {
                $this->downloadDatabase();
            }

            // 使用ip2region查询
            $searcher = new Ip2Region($this->dbPath);
            $result = $searcher->btreeSearch($ip);

            if ($result && isset($result['region'])) {
                return $this->parseResult($ip, $result['region']);
            }

            return $this->getDefaultResult('查询失败');

        } catch (\Exception $e) {
            $this->logger->error('IP地址查询失败', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);
            return $this->getDefaultResult('查询异常');
        }
    }

    /**
     * 解析ip2region的查询结果
     */
    private function parseResult(string $ip, string $result): array
    {
        // ip2region返回格式: 国家|区域|省份|城市|ISP
        $parts = explode('|', $result);
        
        $country = $parts[0] ?? '';
        $region = $parts[1] ?? '';
        $province = $parts[2] ?? '';
        $city = $parts[3] ?? '';
        $isp = $parts[4] ?? '';

        // 清理0值
        $country = $country === '0' ? '' : $country;
        $region = $region === '0' ? '' : $region;
        $province = $province === '0' ? '' : $province;
        $city = $city === '0' ? '' : $city;
        $isp = $isp === '0' ? '' : $isp;

        return [
            'success' => true,
            'ip' => $ip,
            'country' => $country,
            'region' => $region,
            'province' => $province,
            'city' => $city,
            'isp' => $isp,
            'location' => $this->formatLocation($country, $province, $city),
            'full_location' => $result,
            'provider' => 'ip2region'
        ];
    }

    /**
     * 下载ip2region数据库文件
     */
    private function downloadDatabase(): void
    {
        $storageDir = dirname($this->dbPath);
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $url = 'https://github.com/lionsoul2014/ip2region/blob/master/data/ip2region.xdb';
        
        try {
            $this->logger->info('开始下载ip2region数据库文件');
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 300,
                    'user_agent' => 'Mozilla/5.0 (compatible; IP2Region)',
                ]
            ]);
            
            $data = file_get_contents($url, false, $context);
            
            if ($data === false) {
                throw new \Exception('下载数据库文件失败');
            }
            
            file_put_contents($this->dbPath, $data);
            $this->logger->info('ip2region数据库文件下载完成');
            
        } catch (\Exception $e) {
            $this->logger->error('下载ip2region数据库文件失败', ['error' => $e->getMessage()]);
            throw new \Exception('无法下载IP数据库文件: ' . $e->getMessage());
        }
    }

    /**
     * 批量查询IP位置
     */
    public function getBatchLocation(array $ips): array
    {
        $results = [];
        foreach ($ips as $ip) {
            $results[$ip] = $this->getLocation($ip);
        }
        return $results;
    }

    /**
     * 获取客户端真实IP
     */
    public function getRealIp(): string
    {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if ($this->isValidIp($ip) && !$this->isPrivateIp($ip)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * 获取简化的位置信息
     */
    public function getSimpleLocation(string $ip): string
    {
        $info = $this->getLocation($ip);
        return $info['location'] ?? '未知位置';
    }

    /**
     * 获取城市信息
     */
    public function getCity(string $ip): string
    {
        $info = $this->getLocation($ip);
        if ($info['success']) {
            return $info['city'] ?: $info['province'] ?: '未知城市';
        }
        return '未知城市';
    }

    /**
     * 获取运营商信息
     */
    public function getIsp(string $ip): string
    {
        $info = $this->getLocation($ip);
        return $info['isp'] ?? '未知运营商';
    }

    /**
     * 验证IP地址格式
     */
    private function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * 判断是否为内网IP
     */
    private function isPrivateIp(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * 格式化位置信息
     */
    private function formatLocation(string $country, string $province, string $city): string
    {
        $parts = array_filter([$country, $province, $city]);
        return implode(' ', $parts);
    }

    /**
     * 获取默认返回结果
     */
    private function getDefaultResult(string $message = '未知'): array
    {
        return [
            'success' => false,
            'ip' => '',
            'country' => '',
            'region' => '',
            'province' => '',
            'city' => '',
            'isp' => '',
            'location' => $message,
            'full_location' => '',
            'provider' => 'ip2region'
        ];
    }

    /**
     * 更新数据库文件
     */
    public function updateDatabase(): bool
    {
        try {
            if (file_exists($this->dbPath)) {
                unlink($this->dbPath);
            }
            $this->downloadDatabase();
            return true;
        } catch (\Exception $e) {
            $this->logger->error('更新数据库文件失败', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * 获取数据库文件信息
     */
    public function getDatabaseInfo(): array
    {
        if (!file_exists($this->dbPath)) {
            return [
                'exists' => false,
                'path' => $this->dbPath,
                'size' => 0,
                'modified_time' => null
            ];
        }

        return [
            'exists' => true,
            'path' => $this->dbPath,
            'size' => filesize($this->dbPath),
            'modified_time' => date('Y-m-d H:i:s', filemtime($this->dbPath))
        ];
    }
} 