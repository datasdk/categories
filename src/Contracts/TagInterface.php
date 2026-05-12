<?php

namespace DataSDK\Categories\Contracts;

interface TagInterface
{
    public function scopeWithType($query, string $type);
}
