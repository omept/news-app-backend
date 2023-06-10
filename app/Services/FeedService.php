<?php

namespace App\Services;

use App\Models\Category;

class FeedService
{
    static $defaultCategoryName = 'business';
    function defaultCategory(): Category
    {
        return Category::where('name', self::$defaultCategoryName)->first();
    }
}
