<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

class HeightController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        // Accept only from the format: 5 ft 7 in → 170 cm
        if (!preg_match('/^(\d) ft (\d{1,2}) in → (\d{3}) cm$/', $text, $matches)) {
            return $this->invalidHeightResponse();
        }

        $feet = (int) $matches[1];
        $inches = (int) $matches[2];
        $heightInCm = (int) $matches[3];

        // Extra safety check (optional)
        if ($feet < 4 || $feet > 6 || $inches > 11 || $heightInCm < 100 || $heightInCm > 250) {
            return $this->invalidHeightResponse();
        }

        // Save to DB
        $this->saveAnswer($chatId, $state, 'height', $heightInCm, Profile::class);

        return [
            'text' => "✅ Height saved: *{$feet} ft {$inches} in* (~{$heightInCm} cm)",
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    private function invalidHeightResponse(): array
    {
        return [
            'text' => "❌ Please select height using the buttons only. No typing is allowed.",
            'options' => self::getOptions(),
            'halt_flow' => true
        ];
    }


    public static function getQuestion(): string
    {
        return __('messages.ask_height_select');
    }

    public static function getOptions(): array
    {
        $keyboard = [];

        for ($ft = 4; $ft <= 6; $ft++) {
            $row = [];
            for ($in = 0; $in <= 11; $in++) {
                $cm = round($ft * 30.48 + $in * 2.54);
                $label = "{$ft} ft {$in} in → {$cm} cm";
                $row[] = ['text' => $label];

                // 2 buttons per row
                if (count($row) === 2) {
                    $keyboard[] = $row;
                    $row = [];
                }
            }

            if (!empty($row)) {
                $keyboard[] = $row;
            }
        }

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
