<?php

namespace App\Notifications;

use App\Services\Contracts\InvoicesServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public InvoicesServiceContract $invoicesService)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable?->user?->telegram_id ? ['telegram', 'mail'] : ['mail'];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->user->telegram_id)
            // Markdown supported.
            ->content("Hello, {$notifiable->user->name}")
            ->line("\nYour order was created :)")
            ->line("\nThank you!");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        logs()->info(self::class . ' has started');
        $invoice = $this->invoicesService->generate($notifiable);
        return (new MailMessage)
            ->greeting("Hello, {$notifiable->user->name}")
            ->line('Your order was created :)')
            ->line('Read more in invoice attached below.')
            ->attach(Storage::disk('public')->path($invoice->filename), [
                'as' => 'name.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
