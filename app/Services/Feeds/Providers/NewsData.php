<?php

namespace App\Services\Feeds\Providers;

class NewsData extends Provider
{
    public string $name;
    public string $url;
    public string $key;

    function __construct()
    {
        $this->name = 'NewsData';
        $this->url = 'NewsData';
        $this->key = env('NEWS_DATA_API_KEY', '');
    }

    function query(string $country, string $category, string $search = ''): string
    {
        return json_encode([]);
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
        return collect($collection)->map(function ($model) {
            return $this->transform($model);
        })->toArray();
    }
}
