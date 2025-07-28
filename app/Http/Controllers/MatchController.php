<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Profile;
use App\Models\DailyMatch;
use App\Models\Preference;
use App\Models\MatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Profile\TelegramController;
use App\Http\Controllers\Profile\IncomeRangeController;

class MatchController extends TelegramController
{

    // next match previous match
    // public function findMatches($chatId)
    // {
    //     // Step 1: Get preference
    //     $preference = Preference::where('telegram_user_id', $chatId)->first();
    //     if (!$preference) {
    //         return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
    //     }

    //     // Step 2: Get user profile
    //     $userProfile = Profile::where('telegram_user_id', $chatId)->first();
    //     if (!$userProfile || empty($userProfile->gender)) {
    //         return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
    //     }

    //     $userGender = strtolower($userProfile->gender);
    //     $oppositeGender = $userGender === 'male' ? 'female' : 'male';

    //     // Step 3: Get already matched IDs
    //     $shownIds = DailyMatch::where('telegram_user_id', $chatId)->pluck('matched_user_id')->toArray();

    //     // Step 4: Build query
    //     try {
    //         $query = $this->buildQuery($preference, $chatId, $oppositeGender)
    //             ->whereNotIn('id', $shownIds);
    //     } catch (\Exception $e) {
    //         return $this->sendMessage($chatId, "❌ Error building match query.");
    //     }

    //     // Step 5: Get next match
    //     $match = $query->first();

    //     if (!$match) {
    //         // If no more new match, check if any shown matches exist for Previous
    //         $lastMatch = DailyMatch::where('telegram_user_id', $chatId)
    //             ->latest('shown_at')
    //             ->with('matchedProfile') // assuming you have relation set up
    //             ->first();

    //         $buttons = [];

    //         if ($lastMatch && $lastMatch->matchedProfile) {
    //             $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
    //         }

    //         return $this->sendMessage($chatId, "😔 No more matches found.", [
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ])
    //         ]);
    //     }

    //     // Step 6: Save this match to DailyMatch
    //     DailyMatch::create([
    //         'telegram_user_id' => $chatId,
    //         'matched_user_id' => $match->id,
    //         'shown_at' => now(),
    //     ]);

    //     // Step 7: Build match summary
    //     $summary = "*👤 Match Found:*\n";
    //     $summary .= "▪️ *Name:* {$match->name}\n";
    //     $summary .= "▪️ *Gender:* {$match->gender}\n";
    //     $summary .= "▪️ *Caste:* {$match->caste}\n";
    //     $summary .= "▪️ *Height:* {$match->height} ft\n";
    //     $summary .= "▪️ *City:* {$match->city}\n";
    //     // $summary .= "▪️ *Phone:* {$match->phone}\n";

    //     // Step 8: Send image and message
    //     try {
    //         $image = $match->profile_photo ?? 'profile_Pic.jpg';
    //         $path = public_path('uploads/profiles/' . $image);

    //         if (file_exists($path)) {
    //             $this->sendPhoto($chatId, $path);
    //         }
    //         $senderProfile = Profile::where('telegram_user_id', $chatId)->first();

    //         $existingRequest = MatchRequest::where('sender_id', $senderProfile->id)
    //             ->where('receiver_id', $match->id)
    //             ->whereIn('status', ['pending', 'approved'])
    //             ->first();

    //         $buttons = [];

    //         if ($existingRequest) {
    //             if ($existingRequest->status === 'approved') {
    //                 $buttons[] = [['text' => '✅ Request Approved', 'callback_data' => 'noop']];
    //             } elseif ($existingRequest->status === 'pending') {
    //                 $buttons[] = [['text' => '⏳ Request Pending', 'callback_data' => 'noop']];
    //             }
    //         } else {
    //             $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
    //         }

    //         $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];


    //         $this->sendMessage($chatId, $summary, [
    //             'parse_mode' => 'Markdown',
    //             'reply_markup' => json_encode([
    //                 'inline_keyboard' => $buttons
    //             ])
    //         ]);
    //         // cache()->forget("previous_index_user_{$chatId}");

    //     } catch (\Exception $e) {
    //         $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
    //     }
    // }
    public function findMatches($chatId)
    {
        $preference = Preference::where('telegram_user_id', $chatId)->first();
        if (!$preference) {
            return $this->sendMessage($chatId, "❌ Preferences not found. Please complete your preferences first.");
        }

        $userProfile = Profile::where('telegram_user_id', $chatId)->first();
        if (!$userProfile || empty($userProfile->gender)) {
            return $this->sendMessage($chatId, "❌ Your profile or gender information is missing.");
        }

        $userGender = strtolower($userProfile->gender);
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        $shownIds = DailyMatch::where('telegram_user_id', $chatId)->pluck('matched_user_id')->toArray();

        // ----------- LEVEL 1: Strict Preference Match -----------
        try {
            $profiles = $this->buildQuery($preference, $chatId, $oppositeGender);
            // Filter out already shown matches
            $profiles = $profiles->whereNotIn('id', $shownIds)->values();
        } catch (\Exception $e) {
            return $this->sendMessage($chatId, "❌ Error building match query.");
        }

        $match = $profiles->first();

        // ----------- LEVEL 2: Gender, Age, Height Only -----------


        if (!$match) {

            Log::info('Running match 2 : Gender, Age, Height, and Income', [
                'chat_id' => $chatId,
                'opposite_gender' => $oppositeGender,
                'min_age' => $preference->partner_min_age,
                'max_age' => $preference->partner_max_age,
                'min_height' => $preference->partner_min_height,
                'max_height' => $preference->partner_max_height,
                'income_range' => $preference->partner_income_range,
            ]);
            $query = Profile::query()
                ->where('id', '!=', $userProfile->id)
                ->whereRaw('LOWER(gender) = ?', [$oppositeGender])
                ->whereNotIn('id', $shownIds);

            if ($preference->partner_min_age) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
            }
            if ($preference->partner_max_age) {
                $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
            }
            if ($preference->partner_min_height) {
                $query->where('height', '>=', $preference->partner_min_height);
            }
            if ($preference->partner_max_height) {
                $query->where('height', '<=', $preference->partner_max_height);
            }
            if ($preference->partner_religion && strtolower($preference->partner_religion) !== 'any') {
                $query->where('caste', $preference->partner_religion);
            }

            $results = $query->get();

            if ($preference->partner_income_range !== 'Any') {
                $incomeHelper = new IncomeRangeController();
                $prefIncome = $incomeHelper->getMinMax($preference->partner_income_range);

                $results = $results->filter(function ($profile) use ($incomeHelper, $prefIncome) {
                    $profileIncome = $incomeHelper->getMinMax($profile->income_range);
                    return $profileIncome && $prefIncome &&
                        $profileIncome['max'] >= $prefIncome['min'] &&
                        $profileIncome['min'] <= $prefIncome['max'];
                });
            }

            $match = $results->first();
        }

        // ----------- LEVEL 3: Gender + Location (State/City) -----------
        // if (!$match) {
        //     Log::info('Running 3nd level match:', [
        //         'chat_id' => $chatId,
        //         'opposite_gender' => $oppositeGender,
        //     ]);
        //     $query = Profile::query()
        //         ->where('telegram_user_id', '!=', $chatId)
        //         ->whereRaw('LOWER(gender) = ?', [$oppositeGender])
        //         // ->where('state', $userProfile->state)
        //         ->whereNotIn('id', $shownIds);

        //     // Optionally, also filter by city
        //     // ->where('city', $userProfile->city);

        //     $match = $query->first();
        // }

        // ----------- No Match Found -----------
        if (!$match) {
            $lastMatch = DailyMatch::where('telegram_user_id', $chatId)
                ->latest('shown_at')
                ->with('matchedProfile')
                ->first();

            $buttons = [];
            if ($lastMatch && $lastMatch->matchedProfile) {
                $buttons[] = [['text' => '⬅️ Previous Profile', 'callback_data' => 'previous_match']];
            }

            return $this->sendMessage($chatId, "😔 No more matches found.", [
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ])
            ]);
        }

        // ----------- Save this match to DailyMatch -----------
        DailyMatch::create([
            'telegram_user_id' => $chatId,
            'matched_user_id' => $match->id,
            'shown_at' => now(),
        ]);

        // ----------- Build match summary -----------
        $summary = "*👤 Match Found:*\n";
        $summary .= "▪️ *Name:* {$match->name}\n";
        $summary .= "▪️ *Gender:* {$match->gender}\n";
        $summary .= "▪️ *Caste:* {$match->caste}\n";
        $summary .= "▪️ *Height:* {$match->height} ft\n";
        $summary .= "▪️ *City:* {$match->city}\n";

        // ----------- Send image and message -----------
        try {
            $image = $match->profile_photo ?? 'profile_Pic.jpg';
            $path = public_path('uploads/profiles/' . $image);

            if (file_exists($path)) {
                $this->sendPhoto($chatId, $path);
            }
            $senderProfile = Profile::where('telegram_user_id', $chatId)->first();

            // $existingRequest = MatchRequest::where('sender_id', $senderProfile->id)
            //     ->where('receiver_id', $match->id)
            //     ->whereIn('status', ['pending', 'approved'])
            //     ->first();

            $existingRequest = MatchRequest::where(function ($q) use ($senderProfile, $match) {
                $q->where('sender_id', $senderProfile->id)
                    ->where('receiver_id', $match->id);
            })->orWhere(function ($q) use ($senderProfile, $match) {
                $q->where('sender_id', $match->id)
                    ->where('receiver_id', $senderProfile->id);
            })
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            $buttons = [];
            if ($existingRequest) {
                if ($existingRequest->status === 'approved') {
                    $buttons[] = [['text' => '✅ Request Approved', 'callback_data' => 'noop']];
                } elseif ($existingRequest->status === 'pending') {
                    $buttons[] = [['text' => '⏳ Request Pending', 'callback_data' => 'noop']];
                }
            } else {
                $buttons[] = [['text' => '✅ Send Request', 'callback_data' => 'send_request_' . $match->id]];
            }
            $buttons[] = [['text' => '⏭️ Next Match', 'callback_data' => 'next_match']];

            $this->sendMessage($chatId, $summary, [
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $buttons
                ])
            ]);
        } catch (\Exception $e) {
            $this->sendMessage($chatId, $summary, ['parse_mode' => 'Markdown']);
        }
    }

    // private function buildQuery($preference, $excludeUserId, $matchGender)
    // {
    //     $query = Profile::query()
    //         ->where('telegram_user_id', '!=', $excludeUserId)
    //         ->whereRaw('LOWER(gender) = ?', [$matchGender]); // Cross-gender match

    //     $profile = Profile::where('telegram_user_id', $excludeUserId)->first();

    //     if ($preference->partner_min_height) {
    //         $query->where('height', '>=', $preference->partner_min_height);
    //     }
    //     if ($preference->partner_max_height) {
    //         $query->where('height', '<=', $preference->partner_max_height);
    //     }

    //     // Log::info('Min Height:', [$preference->partner_min_height]);
    //     // Log::info('Max Height:', [$preference->partner_max_height]);

    //     // $age = \Carbon\Carbon::parse($profile->dob)->age;
    //     // Log::info("User age:", ['age' => $age]);

    //     if ($preference->partner_min_age) {
    //         $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
    //     }

    //     if ($preference->partner_max_age) {
    //         $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
    //     }

    //     if ($preference->partner_marital_status) {
    //         $query->where('marital_status', $preference->partner_marital_status);
    //     }

    //     if ($preference->partner_caste) {
    //         $query->where('caste', $preference->partner_caste);
    //     }

    //     if ($preference->partner_language) {
    //         $query->where('mother_tongue', $preference->partner_language);
    //     }

    //     // if ($preference->partner_income_range) {
    //     //     $query->where('income_range', $preference->partner_income_range);
    //     // }

    //     if ($preference->partner_religion && $preference->partner_religion !== 'Any') {
    //         $query->where('religion', $preference->partner_religion);
    //     }

    //     if ($preference->partner_job_status && $preference->partner_job_status !== 'Any') {
    //         $query->where('job_status', $preference->partner_job_status);
    //     }
    //     Log::info('SQL:', [$query->toSql()]);
    //     Log::info('Bindings:', [$query->getBindings()]);

    //     return $query->with('galleries');
    // }


    private function buildQuery($preference, $excludeUserId, $matchGender)
    {
        $query = Profile::query()
            ->where('telegram_user_id', '!=', $excludeUserId)
            ->whereRaw('LOWER(gender) = ?', [$matchGender]);

        $profile = Profile::where('telegram_user_id', $excludeUserId)->first();

        if ($preference->partner_min_height) {
            $query->where('height', '>=', $preference->partner_min_height);
        }
        if ($preference->partner_max_height) {
            $query->where('height', '<=', $preference->partner_max_height);
        }
        if ($preference->partner_min_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= ?", [$preference->partner_min_age]);
        }
        if ($preference->partner_max_age) {
            $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= ?", [$preference->partner_max_age]);
        }

        if (
            $preference->partner_marital_status &&
            strtolower($preference->partner_marital_status) !== 'any'
        ) {
            $query->where('marital_status', $preference->partner_marital_status);
        }

        if ($preference->partner_religion && strtolower($preference->partner_religion) !== 'any') {
            $query->where('religion', $preference->partner_religion);
        }

        if ($preference->partner_language && strtolower($preference->partner_language) !== 'any') {
            $query->where('mother_tongue', $preference->partner_language);
        }

        if ($preference->partner_job_status && $preference->partner_job_status !== 'any') {
            $query->where('job_status', $preference->partner_job_status);
        }

        Log::info('SQL:', [$query->toSql()]);
        Log::info('Bindings:', [$query->getBindings()]);

        // Fetch all profiles matching other filters
        $profiles = $query->with('galleries')->get();

        // Filter by income range overlap in PHP
        if ($preference->partner_income_range && $preference->partner_income_range !== 'Any') {
            $incomeHelper = new \App\Http\Controllers\Profile\IncomeRangeController();
            $prefIncome = $incomeHelper->getMinMax($preference->partner_income_range);

            $profiles = $profiles->filter(function ($profile) use ($incomeHelper, $prefIncome) {
                $profileIncome = $incomeHelper->getMinMax($profile->income_range);

                $overlap = $profileIncome && $prefIncome
                    ? ($profileIncome['max'] >= $prefIncome['min'] && $profileIncome['min'] <= $prefIncome['max'])
                    : false;

                Log::info('Income Range Debug first', [
                    'profile_id' => $profile->id,
                    'profile_income' => $profile->income_range,
                    'profile_min' => $profileIncome['min'] ?? null,
                    'profile_max' => $profileIncome['max'] ?? null,
                    'pref_min' => $prefIncome['min'] ?? null,
                    'pref_max' => $prefIncome['max'] ?? null,
                    'overlap' => $overlap ? 'MATCH' : 'NO MATCH'
                ]);

                return $overlap;
            });
        }

        return $profiles;
    }
}
