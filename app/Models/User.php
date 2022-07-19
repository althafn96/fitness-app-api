<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HybridRelations;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'display_picture',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Has Many relationship with user
     *
     * @return HasMany
     */
    public function dailySteps()
    {
        return $this->hasMany(DailyStep::class);
    }

    /**
     * custom attribute to fetch the user steps count for the current day
     *
     * @return Integer
     */
    public function getCurrentDayStepsCountAttribute()
    {
        $currentDayStepsCount = $this->dailySteps->where('start_time', '<', Carbon::now()->toDateTimeString())->where('end_time', '>', Carbon::now()->toDateTimeString())->first();
        return $currentDayStepsCount ? $currentDayStepsCount->stepsCount : 0;
    }

    
    public static function booted()
    {
        static::creating(function ($user) {

            $user->display_picture = "https://robohash.org/" .$user->email ."?gravatar=yes";
        });
    }
}
