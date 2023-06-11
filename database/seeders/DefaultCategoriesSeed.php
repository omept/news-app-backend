<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DefaultCategoriesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [];
        $names = ['business', 'sports', 'entertainment','news'];
        for ($i = 0; $i < count($names); $i++) {
            $categories[] = [
                'name' => $names[$i],
                'description' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        Category::insert($categories);
    }
}
