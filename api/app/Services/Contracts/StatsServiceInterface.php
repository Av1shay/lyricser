<?php


namespace App\Services\Contracts;


interface StatsServiceInterface
{
    public function getStats(): array;
    public function updateStats(int $charsCount, int $wordsCount, int $stanzasCount, int $songsCount): void;
}
