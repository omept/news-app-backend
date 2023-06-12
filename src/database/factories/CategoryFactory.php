<?php

namespace Database\Factories;

use App\Services\Feeds\FeedService;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => FeedService::$defaultCategoryName,
            'description' => ''
        ];
    }
}
