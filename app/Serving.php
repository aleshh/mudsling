<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Beverage;
use App\User;

class Serving extends Model
{
    protected $guarded = [];

    public function beverage() {
        return $this->belongsTo(Beverage::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function todayCount() {
        return Serving::where('created_at', '>=', Carbon::today())
            ->where('user_id', Auth::id())
            ->get()
            ->count();
    }

    public static function todayAlcohol() {
        $servings = Serving::where('created_at', '>=', Carbon::today())            ->where('user_id', Auth::id())
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

}
