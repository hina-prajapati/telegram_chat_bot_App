<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\DailyMatch;
use App\Models\Preference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MatchController;

class SendDailyMatches extends Command
{
    protected $signature = 'matches:daily';
    protected $description = 'Send 1 match per day to each eligible user';

       public function handle()
    {
        $controller = new MatchController(); // Replace with actual class name

        // Loop through all users
        $profiles = Profile::pluck('telegram_user_id')->unique();

        foreach ($profiles as $chatId) {
            $controller->findMatches($chatId);
            sleep(1); // optional: avoid flooding Telegram API
        }

        $this->info('Daily matches sent successfully.');
    }

}
