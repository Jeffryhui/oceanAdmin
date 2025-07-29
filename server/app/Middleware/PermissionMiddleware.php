<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Annotation\Permission;
use App\Model\Permission\SystemUser;
use App\Service\Permission\MenuService;
use App\Utils\Response;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PermissionMiddleware implements MiddlewareInterface
{
    #[Inject]
    private MenuService $menuService;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 先获取到permission注解
        $route = $request->getAttribute(Dispatched::class);
        if ($route && $route->handler && is_array($route->handler->callback)) {
            $callback = $route->handler->callback;
            [$className, $methodName] = $callback;
            $methodAnnotations = AnnotationCollector::getClassMethodAnnotation($className, $methodName);
            
            if (isset($methodAnnotations[Permission::class])) {
                $permissionAnnotation = $methodAnnotations[Permission::class];
                $isAuth = $permissionAnnotation->isAuth;
                $code = $permissionAnnotation->code;
                $isWhite = $permissionAnnotation->isWhite;
                
                // 不需要认证或白名单，直接通过
                if (!$isAuth || $isWhite) {
                    return $handler->handle($request);
                }
                
                /**
                 * @var SystemUser $user
                 */
                $user = auth('admin')->user();
                
                
                // 检查用户状态是否正常
                if ($user->status != SystemUser::STATUS_NORMAL) {
                    return Response::error('用户已被禁用', 403, 403);
                }
                
                // 超级管理员拥有所有权限
                if ($user->id == SystemUser::SUPER_ADMIN_ID) {
                    return $handler->handle($request);
                }
                
                // 检查菜单权限（使用缓存）
                $userMenuCodes = $this->menuService->getUserMenuCodes($user->id);
                if (!in_array('*', $userMenuCodes) && !in_array($code, $userMenuCodes)) {
                    return Response::error('无权限访问', 403, 403);
                }
            }
        }
        return $handler->handle($request);
    }
}
