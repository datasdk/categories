<?php

namespace DataSDK\Categories\Contracts;

interface CategoryInterface
{
    public function getMorphClass();

    public function model();

    public function entries($class);

    public static function getAllChildren($ids = null);

    public function getChildrenAttribute();

    public static function addInclude(string $type, string $class);
}
