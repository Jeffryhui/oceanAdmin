<?php

namespace App\Utils;

class Tree
{
    public static function build(array $menus, int $parentId = 0)
    {
        $tree = [];
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $children = self::build($menus, $menu['id']);
                if ($children) {
                    $menu['children'] = $children;
                } else {
                    $menu['children'] = [];
                }
                $tree[] = $menu;
            }
        }
        return $tree;
    }
}
