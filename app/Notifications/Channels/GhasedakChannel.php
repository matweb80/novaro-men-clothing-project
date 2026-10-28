<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Models\User;

class GhasedakChannel
{
    public function send(Notification $notifiable){
        return $notifiable;
    }
}