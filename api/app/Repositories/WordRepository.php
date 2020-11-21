<?php


namespace App\Repositories;

use App\Repositories\Contracts\WordRepositoryInterface;
use Illuminate\Support\Facades\DB;

class WordRepository implements WordRepositoryInterface
{
    protected $table = 'words';

    public function insertsWithTransaction(array $data): bool
    {
        return DB::transaction(function () use ($data) {
            DB::table($this->table)->insert($data);
        });
    }
}
