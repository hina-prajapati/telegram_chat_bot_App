<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;


class MotherTongueController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validMotherTongues = [
            __('messages.mother_tongue_hindi'),
            __('messages.mother_tongue_bengali'),
            __('messages.mother_tongue_marathi'),
            __('messages.mother_tongue_telugu'),
            __('messages.mother_tongue_tamil'),
            __('messages.mother_tongue_gujarati'),
            __('messages.mother_tongue_urdu'),
            __('messages.mother_tongue_kannada'),
            __('messages.mother_tongue_odia'),
            __('messages.mother_tongue_malayalam'),
            __('messages.mother_tongue_punjabi'),
            __('messages.mother_tongue_assamese'),
            __('messages.mother_tongue_maithili'),
            __('messages.mother_tongue_konkani'),
            __('messages.mother_tongue_dogri'),
            __('messages.mother_tongue_kashmiri'),
            __('messages.mother_tongue_manipuri'),
            __('messages.mother_tongue_nepali'),
            __('messages.mother_tongue_bodo'),
            __('messages.mother_tongue_santali'),
            __('messages.mother_tongue_sanskrit'),
            __('messages.mother_tongue_sindhi'),
            __('messages.mother_tongue_tulu'),
            __('messages.mother_tongue_bhojpuri'),
            __('messages.mother_tongue_haryanvi'),
            __('messages.mother_tongue_kutchhi'),
            __('messages.mother_tongue_marwari'),
            __('messages.mother_tongue_english'),
            __('messages.mother_tongue_other'),
        ];

        if (!in_array($text, $validMotherTongues)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'mother_tongue', $text, Profile::class);

        return [
            'text' => __('messages.thanks_mother_tongue', ['tongue' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_mother_tongue');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => __('messages.mother_tongue_hindi')],
                        ['text' => __('messages.mother_tongue_bengali')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_marathi')],
                        ['text' => __('messages.mother_tongue_telugu')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_tamil')],
                        ['text' => __('messages.mother_tongue_gujarati')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_urdu')],
                        ['text' => __('messages.mother_tongue_kannada')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_odia')],
                        ['text' => __('messages.mother_tongue_malayalam')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_punjabi')],
                        ['text' => __('messages.mother_tongue_assamese')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_maithili')],
                        ['text' => __('messages.mother_tongue_konkani')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_dogri')],
                        ['text' => __('messages.mother_tongue_kashmiri')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_manipuri')],
                        ['text' => __('messages.mother_tongue_nepali')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_bodo')],
                        ['text' => __('messages.mother_tongue_santali')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_sanskrit')],
                        ['text' => __('messages.mother_tongue_sindhi')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_tulu')],
                        ['text' => __('messages.mother_tongue_bhojpuri')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_haryanvi')],
                        ['text' => __('messages.mother_tongue_kutchhi')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_marwari')],
                        ['text' => __('messages.mother_tongue_english')],
                    ],
                    [
                        ['text' => __('messages.mother_tongue_other')],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),

        ];
    }
}
