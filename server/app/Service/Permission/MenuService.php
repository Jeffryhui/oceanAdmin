<?php

namespace App\Service\Permission;

use App\Model\Permission\Menu;
use App\Service\BaseService;
use App\Service\IService;

class MenuService extends BaseService implements IService
{
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }
    public function getUserMenus()
    {
        // TODO 从数据库查询用户对应的菜单
        $routers = [
            [
                'id' => 1000,
                'parent_id' => 0,
                'name' => 'permission',
                'path' => '/permission',
                'component' => '',
                'redirect' => null,
                'meta' => [
                    'title' => '权限',
                    'type' => 'M',
                    'hidden' => false,
                    'layout' => true,
                    'hiddenBreadcrumb' => false,
                    'icon' => 'IconSafe'
                ],
                'children' => [
                    [
                        'id' => 1100,
                        'parent_id' => 1000,
                        'name' => 'permission/user',
                        'path' => '/permission/user',
                        'component' => 'system/user/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '用户管理',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconUserGroup'
                        ]
                    ],
                    [
                        'id' => 1200,
                        'parent_id' => 1000,
                        'name' => 'permission/menu',
                        'path' => '/permission/menu',
                        'component' => 'system/menu/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '菜单管理',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconMenu'
                        ]
                    ],
                    [
                        'id' => 1400,
                        'parent_id' => 1000,
                        'name' => 'permission/role',
                        'path' => '/permission/role',
                        'component' => 'system/role/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '角色管理',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconLock'
                        ]
                    ],
                ]
            ],
            [
                'id' => 2000,
                'parent_id' => 0,
                'name' => 'data',
                'path' => '/data',
                'component' => '',
                'redirect' => null,
                'meta' => [
                    'title' => '数据',
                    'type' => 'M',
                    'hidden' => false,
                    'layout' => true,
                    'hiddenBreadcrumb' => false,
                    'icon' => 'IconStorage'
                ],
                'children' => [
                    [
                        'id' => 2100,
                        'parent_id' => 2000,
                        'name' => 'data/dict',
                        'path' => '/data/dict',
                        'component' => 'system/dict/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '数据字典',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconBook'
                        ]
                    ],
                    [
                        'id' => 2200,
                        'parent_id' => 2000,
                        'name' => 'data/attachment',
                        'path' => '/data/attachment',
                        'component' => 'system/attachment/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '附件管理',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconAttachment'
                        ]
                    ],
                ]
            ],
            [
                'id' => 3000,
                'parent_id' => 0,
                'name' => 'monitor',
                'path' => '/monitor',
                'component' => '',
                'redirect' => null,
                'meta' => [
                    'title' => '监控',
                    'type' => 'M',
                    'hidden' => false,
                    'layout' => true,
                    'hiddenBreadcrumb' => false,
                    'icon' => 'IconComputer'
                ],
                'children' => [
                    [
                        'id' => 3200,
                        'parent_id' => 3000,
                        'name' => 'monitor/server',
                        'path' => '/monitor/server',
                        'component' => 'system/monitor/server/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '服务监控',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconDashboard'
                        ]
                    ],
                    [
                        'id' => 3300,
                        'parent_id' => 3000,
                        'name' => 'monitor/logs',
                        'path' => '/monitor/logs',
                        'component' => '',
                        'redirect' => null,
                        'meta' => [
                            'title' => '日志监控',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconRobot'
                        ],
                        'children' => [
                            [
                                'id' => 3400,
                                'parent_id' => 3300,
                                'name' => 'monitor/logs/loginLog',
                                'path' => '/monitor/logs/loginLog',
                                'component' => 'system/logs/loginLog',
                                'redirect' => null,
                                'meta' => [
                                    'title' => '登录日志',
                                    'type' => 'M',
                                    'hidden' => false,
                                    'layout' => true,
                                    'hiddenBreadcrumb' => false,
                                    'icon' => 'IconImport'
                                ]
                            ],
                            [
                                'id' => 3500,
                                'parent_id' => 3300,
                                'name' => 'monitor/logs/operLog',
                                'path' => '/monitor/logs/operLog',
                                'component' => 'system/logs/operLog',
                                'redirect' => null,
                                'meta' => [
                                    'title' => '操作日志',
                                    'type' => 'M',
                                    'hidden' => false,
                                    'layout' => true,
                                    'hiddenBreadcrumb' => false,
                                    'icon' => 'IconInfoCircle'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'id' => 4000,
                'parent_id' => 0,
                'name' => 'tool',
                'path' => '/tool',
                'component' => '',
                'redirect' => null,
                'meta' => [
                    'title' => '工具',
                    'type' => 'M',
                    'hidden' => false,
                    'layout' => true,
                    'hiddenBreadcrumb' => false,
                    'icon' => 'IconTool'
                ],
                'children' => [
                    [
                        'id' => 4100,
                        'parent_id' => 4000,
                        'name' => 'tool/code',
                        'path' => '/tool/code',
                        'component' => 'tool/code/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '代码生成器',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconCodeSquare'
                        ]
                    ],
                    [
                        'id' => 4200,
                        'parent_id' => 4000,
                        'name' => 'tool/crontab',
                        'path' => '/tool/crontab',
                        'component' => 'tool/crontab/index',
                        'redirect' => null,
                        'meta' => [
                            'title' => '定时任务',
                            'type' => 'M',
                            'hidden' => false,
                            'layout' => true,
                            'hiddenBreadcrumb' => false,
                            'icon' => 'IconSchedule'
                        ]
                    ]
                ]
            ],
            [
                'id' => 5000,
                'parent_id' => 0,
                'name' => 'config',
                'path' => '/config',
                'component' => 'system/config/index',
                'redirect' => null,
                'meta' => [
                    'title' => '系统设置',
                    'type' => 'M',
                    'hidden' => false,
                    'layout' => true,
                    'hiddenBreadcrumb' => false,
                    'icon' => 'IconSettings'
                ]
            ]
        ];
        return $routers;
    }
}
