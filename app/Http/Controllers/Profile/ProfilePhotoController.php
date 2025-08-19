<?php

namespace App\Http\Controllers\Profile;

use App\Models\Profile;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;

class ProfilePhotoController extends Controller
{
    public function handle($chatId, $photoOrText, TelegramUserState $state)
    {
        $skipCommands = [
            'en' => ['leave', 'done'],
            'hi' => ['फोटो छोड़ें', 'पूरा हुआ'],
            'mr' => ['फोटो वगळा', 'झाले'],
            'gu' => ['ફોટો છોડો', 'પૂર્ણ થયું']
        ];

        $currentLang = $state->language ?? 'en';
        $localizedSkips = $skipCommands[$currentLang] ?? $skipCommands['en'];

        $profile = Profile::where('telegram_user_id', $chatId)->first();
        $uploadedCount = $profile ? $profile->gallery()->count() : 0;

        if (is_string($photoOrText)) {
            $text = mb_strtolower(trim($photoOrText));

            if (in_array($text, array_map('mb_strtolower', $localizedSkips))) {
                if ($uploadedCount >= 1) {
                    return [
                        'text' => __('messages.profile_photo_skipped'),
                        'options' => ['parse_mode' => 'Markdown']
                    ];
                } else {
                    return [
                        'text' => __('messages.profile_photo_required'),
                        'options' => ['parse_mode' => 'Markdown']
                    ];
                }
            }

            return ['text' => __('messages.profile_photo_invalid_text')];
        }

        if (!is_array($photoOrText)) {
            return ['text' => __('messages.profile_photo_invalid')];
        }

        if ($uploadedCount >= 2) {
            return ['text' => __('messages.profile_photo_limit_reached')];
        }

        $fileId = end($photoOrText)['file_id'] ?? null;
        if (!$fileId) {
            return ['text' => __('messages.profile_photo_process_failed')];
        }

        $filename = $this->downloadAndSaveProfilePhoto($fileId);
        if (!$filename) {
            return ['text' => __('messages.profile_photo_save_failed')];
        }

        if ($profile) {
            Gallery::create([
                'profile_id' => $profile->id,
                'image_path' => $filename
            ]);
        }

        $uploadedCount++;

        // If 2 images uploaded, suggest proceeding
        if ($uploadedCount >= 2) {
            return [
                'text' => __('messages.profile_photo_max_reached'),
                'options' => [
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [['text' => __('messages.skip_photo')]]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => false
                    ])
                ]
            ];
        }

        // Allow user to upload more or skip
        return [
            'text' => __('messages.profile_photo_uploaded'),
            'options' => [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [['text' => __('messages.skip_photo')]]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => false
                ])
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_profile_photo');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.skip_photo')]]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    public function downloadAndSaveProfilePhoto($fileId)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        $fileInfoUrl = "https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}";
        $fileResponse = Http::get($fileInfoUrl);
        if (!$fileResponse->ok()) {
            Log::error("❌ Failed to get file info", ['response' => $fileResponse->body()]);
            return null;
        }

        $filePath = $fileResponse->json()['result']['file_path'];
        $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";

        $fileContent = file_get_contents($fileUrl);
        if (!$fileContent) {
            Log::error("❌ Failed to download file", ['url' => $fileUrl]);
            return null;
        }

        $filename = 'profile_' . time() . '_' . uniqid() . '.jpg';
        $destination = public_path('uploads/profiles');

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        file_put_contents("{$destination}/{$filename}", $fileContent);

        return $filename;
    }
}
