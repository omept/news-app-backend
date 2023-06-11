<?php

namespace App\Services\Feeds\Providers;

class NewsAPI extends Provider
{
    public string $name;
    public string $url;
    public string $key;

    function __construct()
    {
        $this->name = 'NewsApi';
        $this->url = 'NewsApi';
        $this->key = env('NEWS_API_KEY', '');
    }

    function query(string $country, string $category, string $search = ''): array
    {
        return [];
    }

    public function transform(array $feed): array
    {
       
        return [
            'image' => '',
            'title' => '',
            'description' => '',
            'author' => 'Lorem Emma',
            'date' => "",
            'link' => '',
        ];
    }

    public function collect(array $collection): array
    {
        return collect($collection)->map(function ($model)   {
            return $this->transform($model);
        })->toArray();
    }
}
