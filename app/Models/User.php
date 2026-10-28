<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_type',
        'phone_number',
        'is_superuser',
        'is_staff',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function isSuperUser(){
        return $this->is_superuser;
    }
    
    public function isStaffUser(){
        return $this->is_staff;
    }

    public function activeCode(){
        return $this->hasMany(ActiveCode::class);
    }

    public function hasTwoFactor($key){
        return $this->two_factor_type == $key;
    }

    public function hasTwoFactorAuthenticatedEnabled(){
        return $this->two_factor_type !== 'off';
    }
    public function hasSmsTwoFactorAuthenticationEnabled(){
        return $this->two_factor_type == 'sms';
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }
    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('approved', 1);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class)->latestOfMany();
    }

    public function hasRole($roles){
        return !! $roles->intersect($this->roles)->all();
    }

    public function hasPermission($permission){
        return $this->permissions->contains('name' , $permission->name) || $this->hasRole($permission->roles);
    }
}
