<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WowinNotification extends Notification
{
    use Queueable;

    private $title;
    private $message;
    private $icon;

    // Menerima data judul, pesan, dan jenis ikon
    public function __construct($title, $message, $icon = 'default')
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
    }

    // Perintahkan Laravel HANYA menyimpannya ke Database 
    // (Karena FCM sudah kita urus sendiri di ChatController)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Format data yang akan disimpan ke database
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
        ];
    }
}