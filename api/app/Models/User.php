<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Kodeine\Metable\Metable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Metable;

    protected $metaTable = 'users_meta';

    public $defaultMetaValues = [
        'words_bags' => [],
        'expressions' => [],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getExpressionById(string $expId) : ?string
    {
        $exps = $this->getMeta('expressions');
        $ids = [];

        if (count($exps) > 0) {
            $ids = Arr::pluck($exps, 'id');
        }

        if (($expIndex = array_search($expId, $ids)) === false) {
            Log::warning('Could not find expression with ID #' . $expId . ' for the user', [
                'userId' => $this->id,
                'userExpressions' => json_encode($exps),
            ]);
            return null;
        }

        return $exps[$expIndex]['expression'];
    }
}
