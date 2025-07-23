<?php

namespace App\Utils;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Context\ApplicationContext;

class ServerInfo
{
    /**
     * 获取浏览器信息
     * 
     * @param RequestInterface|string|null $request 请求对象或用户代理字符串
     * @param string|null $userAgent 用户代理字符串（当第一个参数为Request时使用）
     * @return array 包含浏览器名称和版本的数组
     */
    public static function browser($request = null, ?string $userAgent = null): array
    {
        // 向后兼容：如果第一个参数是字符串，则作为userAgent处理
        if (is_string($request)) {
            $userAgent = $request;
            $request = null;
        }
        
        $userAgent = $userAgent ?: self::getUserAgent($request);
        
        if (empty($userAgent)) {
            return [
                'name' => 'Unknown',
                'version' => 'Unknown',
                'full' => 'Unknown Browser'
            ];
        }

        $browsers = [
            // Chrome 系列
            '/Chrome\/([0-9.]+)/' => 'Chrome',
            '/CriOS\/([0-9.]+)/' => 'Chrome', // iOS Chrome
            
            // Firefox 系列
            '/Firefox\/([0-9.]+)/' => 'Firefox',
            '/FxiOS\/([0-9.]+)/' => 'Firefox', // iOS Firefox
            
            // Safari 系列
            '/Version\/([0-9.]+).*Safari/' => 'Safari',
            
            // Edge 系列
            '/Edg\/([0-9.]+)/' => 'Edge', // 新版 Edge
            '/Edge\/([0-9.]+)/' => 'Edge Legacy', // 旧版 Edge
            
            // Internet Explorer
            '/MSIE ([0-9.]+)/' => 'Internet Explorer',
            '/Trident.*rv:([0-9.]+)/' => 'Internet Explorer',
            
            // Opera 系列
            '/OPR\/([0-9.]+)/' => 'Opera',
            '/Opera\/([0-9.]+)/' => 'Opera',
            '/OPiOS\/([0-9.]+)/' => 'Opera', // iOS Opera
            
            // 微信内置浏览器
            '/MicroMessenger\/([0-9.]+)/' => 'WeChat',
            
            // QQ 浏览器
            '/QQBrowser\/([0-9.]+)/' => 'QQ Browser',
            
            // UC 浏览器
            '/UCBrowser\/([0-9.]+)/' => 'UC Browser',
            '/UCWEB([0-9.]+)/' => 'UC Browser',
            
            // 360 浏览器
            '/360SE/' => '360 Secure Explorer',
            '/360EE/' => '360 Extreme Explorer',
            
            // 搜狗浏览器
            '/SE ([0-9.]+)/' => 'Sogou Explorer',
            '/MetaSr ([0-9.]+)/' => 'Sogou Explorer',
            
            // 百度浏览器
            '/BIDUBrowser ([0-9.]+)/' => 'Baidu Browser',
            '/baidubrowser ([0-9.]+)/' => 'Baidu Browser',
            
            // 其他移动端浏览器
            '/Mobile\/.*Safari/' => 'Mobile Safari',
        ];

        foreach ($browsers as $pattern => $name) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $version = isset($matches[1]) ? $matches[1] : 'Unknown';
                return [
                    'name' => $name,
                    'version' => $version,
                    'full' => $name . ' ' . $version
                ];
            }
        }

        // 如果都没匹配到，尝试一些特殊检测
        if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            return [
                'name' => 'Safari',
                'version' => 'Unknown',
                'full' => 'Safari Unknown'
            ];
        }

        return [
            'name' => 'Unknown',
            'version' => 'Unknown',
            'full' => 'Unknown Browser'
        ];
    }

    /**
     * 获取操作系统信息
     * 
     * @param RequestInterface|string|null $request 请求对象或用户代理字符串
     * @param string|null $userAgent 用户代理字符串（当第一个参数为Request时使用）
     * @return array 包含操作系统名称和版本的数组
     */
    public static function os($request = null, ?string $userAgent = null): array
    {
        // 向后兼容：如果第一个参数是字符串，则作为userAgent处理
        if (is_string($request)) {
            $userAgent = $request;
            $request = null;
        }
        
        $userAgent = $userAgent ?: self::getUserAgent($request);
        
        if (empty($userAgent)) {
            return [
                'name' => 'Unknown',
                'version' => 'Unknown',
                'full' => 'Unknown OS'
            ];
        }

        $systems = [
            // Windows 系列
            '/Windows NT 10.0/' => ['Windows 10', '10.0'],
            '/Windows NT 6.3/' => ['Windows 8.1', '6.3'],
            '/Windows NT 6.2/' => ['Windows 8', '6.2'],
            '/Windows NT 6.1/' => ['Windows 7', '6.1'],
            '/Windows NT 6.0/' => ['Windows Vista', '6.0'],
            '/Windows NT 5.2/' => ['Windows XP', '5.2'],
            '/Windows NT 5.1/' => ['Windows XP', '5.1'],
            '/Windows NT 5.0/' => ['Windows 2000', '5.0'],
            '/Windows NT/' => ['Windows NT', 'Unknown'],
            '/Windows 95/' => ['Windows 95', '95'],
            '/Windows 98/' => ['Windows 98', '98'],
            '/Win16/' => ['Windows 3.11', '3.11'],
            
            // macOS/Mac OS X 系列
            '/Mac OS X ([0-9_]+)/' => ['macOS', null], // 版本号在匹配组中
            '/Mac OS X/' => ['macOS', 'Unknown'],
            '/Macintosh/' => ['Mac OS', 'Classic'],
            
            // Linux 系列
            '/Linux x86_64/' => ['Linux', 'x86_64'],
            '/Linux i686/' => ['Linux', 'i686'],
            '/Linux armv/' => ['Linux', 'ARM'],
            '/Linux/' => ['Linux', 'Unknown'],
            '/Ubuntu/' => ['Ubuntu', 'Unknown'],
            '/CentOS/' => ['CentOS', 'Unknown'],
            '/Debian/' => ['Debian', 'Unknown'],
            '/Fedora/' => ['Fedora', 'Unknown'],
            '/Red Hat/' => ['Red Hat', 'Unknown'],
            
            // iOS 系列
            '/iPhone OS ([0-9_]+)/' => ['iOS', null], // 版本号在匹配组中
            '/OS ([0-9_]+) like Mac OS X/' => ['iOS', null], // iPad
            '/iPhone/' => ['iOS', 'Unknown'],
            '/iPad/' => ['iPadOS', 'Unknown'],
            '/iPod/' => ['iOS', 'Unknown'],
            
            // Android 系列
            '/Android ([0-9.]+)/' => ['Android', null], // 版本号在匹配组中
            '/Android/' => ['Android', 'Unknown'],
            
            // Windows Phone/Mobile
            '/Windows Phone OS ([0-9.]+)/' => ['Windows Phone', null],
            '/Windows Phone/' => ['Windows Phone', 'Unknown'],
            '/Windows Mobile/' => ['Windows Mobile', 'Unknown'],
            
            // 其他移动系统
            '/BlackBerry/' => ['BlackBerry', 'Unknown'],
            '/BB10/' => ['BlackBerry 10', 'Unknown'],
            '/webOS/' => ['webOS', 'Unknown'],
            '/Symbian/' => ['Symbian', 'Unknown'],
            '/S60/' => ['Symbian S60', 'Unknown'],
            
            // Unix 系列
            '/FreeBSD/' => ['FreeBSD', 'Unknown'],
            '/OpenBSD/' => ['OpenBSD', 'Unknown'],
            '/NetBSD/' => ['NetBSD', 'Unknown'],
            '/SunOS/' => ['SunOS', 'Unknown'],
            '/AIX/' => ['AIX', 'Unknown'],
            
            // 其他
            '/CrOS/' => ['Chrome OS', 'Unknown'],
            '/X11/' => ['Unix', 'Unknown'],
        ];

        foreach ($systems as $pattern => $info) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $name = $info[0];
                $version = $info[1];
                
                // 处理有版本号匹配组的情况
                if ($version === null && isset($matches[1])) {
                    $version = str_replace('_', '.', $matches[1]);
                }
                
                // 特殊处理 macOS 版本号映射
                if ($name === 'macOS' && $version) {
                    $macVersions = [
                        '10.15' => 'Catalina',
                        '10.14' => 'Mojave',
                        '10.13' => 'High Sierra',
                        '10.12' => 'Sierra',
                        '10.11' => 'El Capitan',
                        '10.10' => 'Yosemite',
                        '10.9' => 'Mavericks',
                        '10.8' => 'Mountain Lion',
                        '10.7' => 'Lion',
                        '10.6' => 'Snow Leopard',
                    ];
                    
                    $versionKey = substr($version, 0, 5); // 取前5位，如 "10.15"
                    if (isset($macVersions[$versionKey])) {
                        $version = $version . ' (' . $macVersions[$versionKey] . ')';
                    }
                }
                
                return [
                    'name' => $name,
                    'version' => $version ?: 'Unknown',
                    'full' => $name . ' ' . ($version ?: 'Unknown')
                ];
            }
        }

        return [
            'name' => 'Unknown',
            'version' => 'Unknown',
            'full' => 'Unknown OS'
        ];
    }

    /**
     * 获取设备类型
     * 
     * @param RequestInterface|string|null $request 请求对象或用户代理字符串
     * @param string|null $userAgent 用户代理字符串（当第一个参数为Request时使用）
     * @return string 设备类型：mobile, tablet, desktop
     */
    public static function deviceType($request = null, ?string $userAgent = null): string
    {
        // 向后兼容：如果第一个参数是字符串，则作为userAgent处理
        if (is_string($request)) {
            $userAgent = $request;
            $request = null;
        }
        
        $userAgent = $userAgent ?: self::getUserAgent($request);
        
        if (empty($userAgent)) {
            return 'unknown';
        }

        // 平板检测
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $userAgent)) {
            return 'tablet';
        }

        // 手机检测
        if (preg_match('/(mobile|phone|android|iphone|ipod|blackberry|webos|windows phone)/i', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * 检测是否为移动设备
     * 
     * @param RequestInterface|string|null $request 请求对象或用户代理字符串
     * @param string|null $userAgent 用户代理字符串（当第一个参数为Request时使用）
     * @return bool
     */
    public static function isMobile($request = null, ?string $userAgent = null): bool
    {
        return in_array(self::deviceType($request, $userAgent), ['mobile', 'tablet']);
    }

    /**
     * 获取完整的客户端信息
     * 
     * @param RequestInterface|string|null $request 请求对象或用户代理字符串
     * @param string|null $userAgent 用户代理字符串（当第一个参数为Request时使用）
     * @return array 包含浏览器、操作系统、设备类型等信息
     */
    public static function getClientInfo($request = null, ?string $userAgent = null): array
    {
        // 向后兼容：如果第一个参数是字符串，则作为userAgent处理
        if (is_string($request)) {
            $userAgent = $request;
            $request = null;
        }
        
        $userAgent = $userAgent ?: self::getUserAgent($request);
        
        return [
            'user_agent' => $userAgent,
            'browser' => self::browser($request, $userAgent),
            'os' => self::os($request, $userAgent),
            'device_type' => self::deviceType($request, $userAgent),
            'is_mobile' => self::isMobile($request, $userAgent),
        ];
    }

    /**
     * 获取用户代理字符串
     * 
     * @param RequestInterface|null $request 请求对象
     * @return string
     */
    private static function getUserAgent($request = null): string
    {
        try {
            if ($request === null || !($request instanceof RequestInterface)) {
                // 尝试从容器中获取当前请求
                $container = ApplicationContext::getContainer();
                if ($container->has(RequestInterface::class)) {
                    $request = $container->get(RequestInterface::class);
                }
            }
            
            if ($request instanceof RequestInterface) {
                return $request->getHeaderLine('User-Agent') ?: '';
            }
        } catch (\Throwable $e) {
            // 如果获取失败，回退到 $_SERVER
        }
        
        // 回退到传统方式
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * 获取客户端真实IP地址
     * 
     * @param RequestInterface|null $request 请求对象
     * @return string
     */
    public static function getClientIp($request = null): string
    {
        try {
            if ($request === null || !($request instanceof RequestInterface)) {
                $container = ApplicationContext::getContainer();
                if ($container->has(RequestInterface::class)) {
                    $request = $container->get(RequestInterface::class);
                }
            }
            
            if ($request instanceof RequestInterface) {
                // 检查代理头信息
                $headers = [
                    'X-Forwarded-For',
                    'X-Real-IP',
                    'Client-IP',
                ];
                
                foreach ($headers as $header) {
                    $value = $request->getHeaderLine($header);
                    if ($value) {
                        $ips = explode(',', $value);
                        $ip = trim($ips[0]);
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    }
                }
                
                // 获取服务器变量中的IP
                $serverParams = $request->getServerParams();
                return $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';
            }
        } catch (\Throwable $e) {
            // 如果获取失败，回退到 $_SERVER
        }
        
        // 回退到传统方式
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
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
