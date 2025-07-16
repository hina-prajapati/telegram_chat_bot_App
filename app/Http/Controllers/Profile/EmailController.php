<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\BaseQuestionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUserState;
use App\Models\Profile;

class EmailController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $answers = $state->answers;
        $answers['email'] = $text;
        
        $this->saveAnswer($chatId, $state, 'email', $text, Profile::class);

        // return [
        //     'text' => "✅ Thanks! Your email has been saved.",
        //     'options' => []
        // ];
        return [
            'text' => __('messages.thanks_email', ['email' => $text]),
            'options' => []
        ];
      
    }
    public static function getQuestion(): string
    {
        // return "📧 *What's your email address?*";
        return __('messages.ask_email');
    }
    
    public static function getOptions(array $answers = []): array
    {
        return [
            'reply_markup' => json_encode([
                'force_reply' => true
            ])
        ];
    }

}
