<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;


class OrderAccepted extends Notification
{
    use Queueable;
 private $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
       $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'mail',
            'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('order/'.$this->order->id);
        return (new MailMessage)
            ->greeting('Hello'.$notifiable->name)
            ->line('Your order #'.$this->order->id.'is being processed.')
            ->action('The order can be viewed at the link', $url)
            ->line('Thank you for using our store!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {                         //сохранение в таблице базы данных в data столбце вашей notifications таблицы
        return [
           // 'data' => $this->order->id,
            $this->order
        ];
    }
}
