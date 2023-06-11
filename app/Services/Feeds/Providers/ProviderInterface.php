<?php

namespace App\Services\Feeds\Providers;

interface ProviderInterface {
    public function query(string $country, string $category, string $search): array;
    public function transform(array $feed): array;
    public function collect(array $collection): array;
}