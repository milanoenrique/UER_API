<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $fillable = [
        'name', 'lastname','email', 'password', 'last_logged_in', 'is_locked_out', 'failed_password_attemp', 'last_lockout_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    /*protected $hidden = [
        'password',
    ];*/
}
