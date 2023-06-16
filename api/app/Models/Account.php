<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\MailConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nickname',
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    /**
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function sendMailAdressConfirmationNotification($id)
    {
        $this->notify(new MailConfirmation($id));
    }
}
