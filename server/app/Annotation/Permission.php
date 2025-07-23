<?php

namespace App\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Permission extends AbstractAnnotation
{
    public function __construct(
        public string $code,
        public string $description = '',
        public bool $isWhite = false,
        public bool $isAuth = true,
    ) {
    }
}
