<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Serving;
use App\User;

class Beverage extends Model
{
    // these two lines do the same thing:
    // protected $fillable = ['name', 'category', 'size', 'strength'];
    protected $guarded = [];

    public function servings() {
        return $this->hasMany(Serving::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
