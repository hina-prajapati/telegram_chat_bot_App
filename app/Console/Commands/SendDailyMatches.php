<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Preference;
use App\Models\DailyMatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendDailyMatches extends Command
{
    protected $signature = 'matches:send-daily';
    protected $description = 'Send 1 match per day to each eligible user';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $users = Preference::pluck('telegram_user_id');

        foreach ($users as $chatId) {
            // Skip if already sent today
            $alreadySent = DailyMatch::where('telegram_user_id', $chatId)
                ->where('match_date', $today)
                ->exists();

            if ($alreadySent) {
                Log::info("🔁 Already sent match to user: $chatId");
                continue;
            }

            try {
                app(\App\Http\Controllers\MatchController::class)->findMatches($chatId);

                DailyMatch::create([
                    'telegram_user_id' => $chatId,
                    'match_date' => $today,
                ]);

                Log::info("✅ Sent daily match to: $chatId");
            } catch (\Exception $e) {
                Log::error("❌ Failed for $chatId: " . $e->getMessage());
            }
        }

        $this->info('✅ All matches processed.');
    }
}
