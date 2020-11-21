<?php


namespace App\Repositories\Contracts;


interface WordRepositoryInterface
{
    public function insertsWithTransaction(array $data): bool;
}
