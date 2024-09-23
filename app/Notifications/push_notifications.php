<?php

// namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Notifications\Notification;
// use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
// use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;
// // use Kreait\Firebase\Messaging\CloudMessage;
// // use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

// class push_notification extends Notification
// {
//     use Queueable;

//     private $title;
//     private $body;
//     private $deviceToken;

//     public function __construct($title, $body, $deviceToken)
//     {
//         $this->title = $title;
//         $this->body = $body;
//         $this->deviceToken = $deviceToken;
//     }

//     public function via($notifiable)
//     {
//         return ['firebase'];
//     }

    
//     public function toFirebase($notifiable)
// {
//     return CloudMessage::new()
//         ->withNotification(FirebaseNotification::create($this->title, $this->body))
//           ->with('token', $this->deviceToken);
// }


// }



namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class push_notification extends Notification
{
    use Queueable;

    private $title;
    private $body;
    private $deviceToken;

    public function __construct($title, $body, $deviceToken)
    {
        $this->title = $title;
        $this->body = $body;
        $this->deviceToken = $deviceToken;
    }

    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        return CloudMessage::withTarget('token', $this->deviceToken)
            ->withNotification(FirebaseNotification::create($this->title, $this->body));
    }
}
