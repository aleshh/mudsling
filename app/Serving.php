<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use User\User;
use Carbon\Carbon;

use App\Beverage;

class Serving extends Model
{
    protected $guarded = [];
    protected $touches = ['beverage'];

    public function beverage() {
        return $this->belongsTo(Beverage::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function todayCount() {
        return Serving::where('local_time', '>=', \App\User::today())
            ->where('user_id', Auth::id())
            ->get()
            ->count();
    }

    public static function todayAlcohol() {
        $servings = Serving::where('local_time', '>=', \App\User::today())            ->where('user_id', Auth::id())
            ->where('user_id', Auth::id())
            ->get();

        $todayAlcohol = 0;

        foreach ($servings as $serving) {
            $todayAlcohol += $serving->beverage->size *
                            $serving->beverage->strength / 100;
        }

        // round to 1 decimal place if under 10, otherwise to 0 decimal places
        if ($todayAlcohol > 9.5) {
            $todayAlcohol = round($todayAlcohol, 0);
        } else {
            $todayAlcohol = round($todayAlcohol, 1);
        }
        return $todayAlcohol;
    }

    public static function todayPercentage() {
        $maxConsumption = Auth::user()->maximumConsumption;
        if ($maxConsumption) {
            return static::todayAlcohol() / $maxConsumption * 100;
        } else {
            return 0;
        }
    }

    public static function nameOfDay($day) {
        $today = Carbon::today();
        $day = Carbon::parse($day);

        if ($day->isToday())     return "Today";
        if ($day->isYesterday()) return "Yesterday";
        $daysAgo = $day->diffInDays($today);

        if ($daysAgo < 7 ) return $day->format('l');
        return $day->format('F d, Y');
    }

}
