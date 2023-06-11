<?php

namespace App\Services\Feeds\Providers;

interface ProviderInterface {
    public function query(string $country, string $category, string $search): string;
    public function transform(array $feed): array;
    public function collect(array $collection): array;
}