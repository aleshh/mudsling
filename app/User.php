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

        // prevent crash on mobile safari webapp initial load this might cause
        // status bar to show wrong information before page refresh
        // better solution would be to save client offset with user record
        if (!isset($_COOKIE['clientTime'])) return Carbon::today();

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
