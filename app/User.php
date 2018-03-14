<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'maximumConsumption'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function today() {

        $clientTime = new Carbon($_COOKIE['clientTime']);
        $clientOffset = $clientTime->timezone->getName();
        $today = new Carbon();
        $today->setTimezone($clientOffset);
        $today->hour = 0;
        $today->minute = 0;
        $today->second;

        return $today;
    }
}
