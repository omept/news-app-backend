<?php

namespace App\Services\Feeds;

use App\Models\Category;
use App\Models\User;

class FeedService
{

    static $defaultCategoryName = 'business';
    static $supportedCountries = ['germany', 'usa', 'china', 'nigeria'];
    static $feedProviders = [
        ['key' => 'NewscatcherApi', 'name' => 'NewscatcherApi.com', 'description' => 'Search worldwide news articles published online'],
        ['key' => 'NewsData', 'name' => 'NewsData.io', 'description' => 'Get live breaking news or search historical news data for the past 2 years from 15453+ sources'],
        ['key' => 'NewsApi', 'name' => 'NewsApi.org', 'description' => 'Locate articles and breaking news headlines from news sources and blogs across the web'],
    ];
    private ?User $user = null;
    private ?Adapter $adapter = null;

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

    function supportedCategories(): array
    {
        return Category::select('name')->get()->pluck('name')->toArray();
    }

    function defaultCountry(): string
    {
        return self::$supportedCountries[0];
    }

    function defaultNewsProvider(): array
    {
        return self::$feedProviders[0];
    }

    function supportedProviders(): array
    {
        $supportedProviders = [];
        for ($i = 0; $i < count(self::$feedProviders); $i++) {
            $supportedProviders[] = self::$feedProviders[$i]['key'];
        }
        return $supportedProviders;
    }

    private function settings(): array
    {
        $userSettings = [];
        if ($this->user) {
            $userSettings = json_decode($this->user->settings, true);
        }

        return [
            'country' => $userSettings['country'] ?? self::defaultCountry(),
            'category' => $userSettings['category'] ?? self::$defaultCategoryName,
        ];
    }


    function feeds(array $config = []): array
    {
        $settings = $this->settings();
        $search = $config['search'] ?? '';
        $country = $config['country'] ?? $settings['country'];
        $category = $config['category'] ?? $settings['category'];
        $body = $this->adapter->query($country,  $category,  $search);
        return json_decode($body, true);
    }

    function meta(): array
    {
        return [
            'countries' => self::$supportedCountries,
            'providers' => self::$feedProviders,
            'categories' => Category::select('name')->get()->toArray(),
        ];
    }
}
