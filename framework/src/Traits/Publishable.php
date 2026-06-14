<?php

namespace R2Packages\Framework\Traits;

trait Publishable
{
    public static function filePath()
    {
        $reflection = new \ReflectionClass(static::class);

        return $reflection->getFileName();
    }
}