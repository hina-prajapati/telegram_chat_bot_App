<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\TelegramUserState;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GalleryPhotoController extends Controller
{
    public function handle($chatId, $photo, TelegramUserState $state, $user)
    {
        $fileId = end($photo)['file_id'] ?? null;

        if (!$fileId) {
            return [
                'text' => "❌ Could not process the image. Please send a valid photo."
            ];
        }

        $filename = $this->downloadAndSaveGalleryPhoto($fileId);

        if (!$filename) {
            return [
                'text' => "❌ Failed to upload. Try again."
            ];
        }

        Gallery::create([
            'telegram_user_id' => $user->id,
            'image_path' => $filename
        ]);

        // Reset offset for fresh match search
        cache()->put("match_offset_user_" . $user->id, 0, now()->addMinutes(30));

        return [
            'text' => "✅ Photo added to your gallery! Let me show some matching profiles...",
            'next' => true
        ];
    }

    public function downloadAndSaveGalleryPhoto($fileId)
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

        $filename = 'gallery_' . time() . '_' . uniqid() . '.jpg';
        $destination = public_path('uploads/gallery');

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        file_put_contents("{$destination}/{$filename}", $fileContent);

        return $filename;
    }
}
