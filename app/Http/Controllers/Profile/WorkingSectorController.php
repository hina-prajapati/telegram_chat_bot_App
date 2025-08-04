<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserState;
use App\Models\Profile;

// class WorkingSectorController extends BaseQuestionController
// {
//     public function handle($chatId, $text, TelegramUserState $state)
//     {
//         $answers = $state->answers;
//         $answers['working_sector'] = $text;

//         $this->saveAnswer($chatId, $state, 'working_sector', $text, Profile::class);

//         return [
//             'text' => __('messages.thanks_working_sector', ['sector' => $text]),
//             'options' => [
//                 'parse_mode' => 'Markdown'
//             ]
//         ];
//     }

//     public static function getQuestion(): string
//     {
//         return __('messages.ask_working_sector');
//     }

//     public static function getOptions(): array
//     {
//         return [
//             'parse_mode' => 'Markdown',
//             'reply_markup' => json_encode([
//                 'keyboard' => [
//                     [['text' => __('messages.sector_private')], ['text' => __('messages.sector_government')]],
//                     [['text' => __('messages.sector_business')], ['text' => __('messages.sector_freelance')]],
//                     [['text' => __('messages.sector_student')], ['text' => __('messages.sector_not_working')]],
//                 ],
//                 'resize_keyboard' => true,
//                 'one_time_keyboard' => true
//             ])
//         ];
//     }
// }

class WorkingSectorController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $validOptions = [
            __('messages.sector_government'),
            __('messages.sector_private'),
            __('messages.sector_business'),
            __('messages.sector_ngo'),
            __('messages.sector_defense'),
            __('messages.sector_education'),
            __('messages.sector_healthcare'),
            __('messages.sector_it'),
            __('messages.sector_finance'),
            __('messages.sector_media'),
            __('messages.sector_hospitality'),
            __('messages.sector_retail'),
            __('messages.sector_manufacturing'),
            __('messages.sector_agriculture'),
            __('messages.sector_real_estate'),
            __('messages.sector_legal'),
            __('messages.sector_arts'),
            __('messages.other'),
        ];

        if (!in_array($text, $validOptions)) {
            return [
                'text' => __('messages.invalid_option'),
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        $this->saveAnswer($chatId, $state, 'working_sector', $text, Profile::class);

        return [
            'text' => __('messages.thanks_working_sector', ['sector' => $text]),
            'options' => [
                'parse_mode' => 'Markdown'
            ]
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_working_sector');
    }

    public static function getOptions(): array
    {
        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [['text' => __('messages.sector_government')], ['text' => __('messages.sector_private')]],
                    [['text' => __('messages.sector_business')], ['text' => __('messages.sector_ngo')]],
                    [['text' => __('messages.sector_defense')], ['text' => __('messages.sector_education')]],
                    [['text' => __('messages.sector_healthcare')], ['text' => __('messages.sector_it')]],
                    [['text' => __('messages.sector_finance')], ['text' => __('messages.sector_media')]],
                    [['text' => __('messages.sector_hospitality')], ['text' => __('messages.sector_retail')]],
                    [['text' => __('messages.sector_manufacturing')], ['text' => __('messages.sector_agriculture')]],
                    [['text' => __('messages.sector_real_estate')], ['text' => __('messages.sector_legal')]],
                    [['text' => __('messages.sector_arts')], ['text' => __('messages.other')]],
                ],

                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }
}
