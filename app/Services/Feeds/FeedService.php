<?php

namespace App\Services\Feeds;

use App\Models\Category;
use App\Models\User;
use App\Services\Feeds\Providers\Provider;

class FeedService
{

    static $defaultCategoryName = 'business';
    static $defaultCountry = 'germany';
    const NewscatcherApi = 'NewscatcherApi'; # default
    const NewsData = 'NewsData';
    const NewsApi = 'NewsApi';
    static $feedProviders = [
        ['name' => 'NewscatcherApi.com', 'description' => 'Search worldwide news articles published online'],
        ['name' => 'NewsData.io', 'description' => 'Get live breaking news or search historical news data for the past 2 years from 15453+ sources'],
        ['name' => 'NewsApi.org', 'description' => 'Locate articles and breaking news headlines from news sources and blogs across the web'],
    ];
    private ?User $user = null;
    private ?Adapter $adapter = null;
    private $config = [];

    function __construct(User $user = null, array $config = [])
    {
        if ($user) {
            $this->user = $user;
        }

        $this->adapter = new Adapter($user);
    }

    function defaultCategory(): Category
    {
        return Category::where('name', self::$defaultCategoryName)->first();
    }

    function defaultNewsProvider(): array
    {
        return self::$feedProviders[0];
    }

    private function settings(): array
    {
        $userSettings = [];
        if ($this->user) {
            $userSettings = json_decode($this->user->settings, true);
        }

        return [
            'country' => $userSettings['country'] ?? self::$defaultCountry,
            'category' => $userSettings['category'] ?? self::$defaultCategoryName,
        ];
    }


    function feeds(array $config = []): array
    {
        $settings = $this->settings();
        $search = $confiq['search'] ?? '';
        $country = $confiq['country'] ?? $settings['country'];
        $category = $config['category'] ?? $settings['category'];
        $body = $this->adapter->query($country,  $category,  $search);
        return json_decode($body, true);
    }
}
