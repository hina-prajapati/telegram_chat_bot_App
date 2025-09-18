<?php
namespace App\Http\Controllers\Profile;
use App\Models\Profile;
use App\Models\TelegramUserState;
use App\Http\Controllers\BaseQuestionController;

class IncomeRangeController extends BaseQuestionController
{
    public function handle($chatId, $text, TelegramUserState $state)
    {
        $incomeOptions = $this->getIncomeOptions();

        if (!array_key_exists($text, $incomeOptions)) {
            return [
                'text' => '❌ Invalid income range. Please choose from the list below.',
                'options' => self::getOptions(),
                'halt_flow' => true
            ];
        }

        // Save the selected income label to Profile table
        $this->saveAnswer($chatId, $state, 'income_range', $text, Profile::class);

        return [
            'text' => __('messages.income_saved', ['value' => $text]),
            'options' => ['parse_mode' => 'Markdown']
        ];
    }

    public static function getQuestion(): string
    {
        return __('messages.ask_income_range'); // 💰 *Please select your Income Range:*
    }

    public static function getOptions(array $answers = []): array
    {
        $instance = new self();
        $labels = array_keys($instance->getIncomeOptions());
        $chunks = array_chunk($labels, 2);

        return [
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode([
                'keyboard' => array_map(fn($row) => array_map(fn($text) => ['text' => $text], $row), $chunks),
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ])
        ];
    }

    /**
     * Central list of income ranges used for display and filtering
     */
    private function getIncomeOptions(): array
    {
        return [
            '₹0 - ₹1L'      => ['min' => 0,        'max' => 100000],
            '₹1L - ₹2L'     => ['min' => 100000,   'max' => 200000],
            '₹2L - ₹5L'     => ['min' => 200000,   'max' => 500000],
            '₹5L - ₹10L'    => ['min' => 500000,   'max' => 1000000],
            '₹10L - ₹25L'   => ['min' => 1000000,  'max' => 2500000],
            'Above ₹25L'    => ['min' => 2500000,  'max' => 99999999],
            'Other'           => ['min' => null,      'max' => null],
        ];
    }

    /**
     * Optional: if you need min/max later for filtering
     */
    public function getMinMax(string $label): ?array
    {
        $options = $this->getIncomeOptions();
        return $options[$label] ?? null;
    }
}
