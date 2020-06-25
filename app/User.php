<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    public const TYPES = [
        'developer' => 'developer',
        'normal' => 'normal'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * Filterable attributes.
    *
    * @var array
    */
    public $filters = [];

    /**
    * Rules for store a record
    *
    * @var array
    */
    public function rulesForStore() : array
    {
        return [];
    }

    /**
    * Rules for update a record
    *
    * @var array
    */
    public function rulesForUpdate() : array
    {
        return [];
    }

    /**
    * Names of relationships
    *
    * @var array
    */
    public function relationsNames()
    {
        return [];
    }

    public function isDeveloper()
    {
        return $this->type == User::TYPES['developer'];
    }
}
