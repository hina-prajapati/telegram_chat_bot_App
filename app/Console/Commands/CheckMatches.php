<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use Illuminate\Console\Command;
use App\Http\Controllers\MatchController;

class CheckMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new matches for all Telegram users';


    public function handle()
    {
        $controller = new MatchController();
        $users = TelegramUser::all();

        foreach ($users as $user) {
            $controller->findMatches($user->chat_id);
        }

        $this->info('Match check completed.');
    }
}
