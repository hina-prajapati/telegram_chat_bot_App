<?php

namespace App\Notifications;

use App\Models\Gallery;
use App\Models\Profile;
use App\Models\Preference;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use App\Http\Controllers\Profile\TelegramController;

class RequestReceivedNotification extends Notification
{
    use Queueable;

    protected $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

            // return TelegramMessage::create()
        //     ->to($notifiable->telegram_user_id)
        //     ->content("📩 *New Match Request Received!*\n\n👤 *{$this->sender->name}* has sent you a match request.")
        //     ->options([
        //         'parse_mode' => 'Markdown',
        //         'reply_markup' => json_encode([
        //             'inline_keyboard' => [
        //                 [
        //                     ['text' => '✅ Approve', 'callback_data' => 'approve_request_' . $this->sender->id . '_' . $notifiable->id],
        //                     ['text' => '❌ Reject', 'callback_data' => 'reject_request_' . $this->sender->id . '_' . $notifiable->id],
        //                 ]
        //             ]
        //         ])
        //     ]);

    public function toTelegram($notifiable)
    {
        app(TelegramController::class)->showOtherProfile($notifiable->telegram_user_id, $this->sender->id);
        
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("📩 *New Match Request Received!*\n\n👤 *{$this->sender->name}* has sent you a match request.")
            ->options([
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => '✅ Approve', 'callback_data' => 'approve_request_' . $this->sender->id . '_' . $notifiable->id],
                            ['text' => '❌ Reject', 'callback_data' => 'reject_request_' . $this->sender->id . '_' . $notifiable->id],
                        ]
                    ]
                ])
            ]);
    }

}
