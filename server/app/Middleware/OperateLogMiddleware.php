<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Annotation\Permission;
use App\EsModel\OperateLog;
use App\Model\Permission\SystemUser;
use App\Utils\IpLocationUtils;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Request;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function FriendsOfHyperf\Helpers\get_client_ip;

class OperateLogMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container, protected IpLocationUtils $ipLocationUtils,protected Request $request)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ip = get_client_ip();
        // 获取控制器Permission注解
        $serviceName = '未知的操作';
        $route = $request->getAttribute(Dispatched::class);
        if ($route && $route->handler && is_array($route->handler->callback)) {
            $callback = $route->handler->callback;
            [$className, $methodName] = $callback;
            $methodAnnotations = AnnotationCollector::getClassMethodAnnotation($className, $methodName);
            if (isset($methodAnnotations[Permission::class])) {
                $permissionAnnotation = $methodAnnotations[Permission::class];
                if (!$permissionAnnotation->isAuth) {
                    return $handler->handle($request);
                }
                $serviceName = $permissionAnnotation->description;
            }
        }
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $requestData = $this->request->all();

        $data = [
            'username' => $user->username,
            'method' => $request->getMethod(),
            'router' => $request->getUri()->getPath(),
            'service_name' => $serviceName,
            'ip' => $ip,
            'ip_location' => $this->ipLocationUtils->getSimpleLocation($ip),
            'request_data' => json_encode($requestData),
            'remark' => sprintf('完整url: %s,请求参数: %s', $request->getUri()->getPath(), json_encode($requestData)),
            'create_time' => date('Y-m-d H:i:s'),
        ];
        OperateLog::query()->create($data);
        return $handler->handle($request);
    }
}
