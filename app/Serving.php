<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serving extends Model
{
    protected $guarded = [];

    public function beverage() {
        $this->belongsTo(Beverage::class);
    }

    public function user() {
        $this->belongsTo(User::class);
    }

    public static function todayCount() {
        return Serving::where('created_at', '>=', Carbon::today())
            ->get()
            ->count();
    }

    public static function todayAlcohol() {
        $servings = Serving::where('created_at', '>=', Carbon::today())
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

}
