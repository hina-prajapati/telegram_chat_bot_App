<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Profile;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        // Optional: Fetch linked preference
        $preference = Preference::where('profile_id', $id)->first();

        $telegramUserId = request()->get('chat_id'); // or get from session/token

        if ($profile->telegram_user_id != $telegramUserId) {
            abort(403, 'Unauthorized access.');
        }

        $states = DB::table('states')->get();

        $state = DB::table('states')->where('name', $profile->state)->first();
        // Log::info("city", [$state]);

        $cities = [];

        if ($state) {
            $cities = DB::table('cities')->where('state_id', $state->id)->get();
            Log::info("city", [$cities]);
        }
        // Log::info("city", [$cities]);

        return view('profiles.edit', compact('profile', 'preference', 'states', 'cities'));
    }



    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required',
            'bio' => 'required',
            'email' => 'required',
            'marital_status' => 'required',
            'state' => 'required',
            'city' => 'required',
            'mother_tongue' => 'required',
            'religion' => 'required',
            'caste' => 'required',
            'education_level' => 'required',
            'education_field' => 'required',
            'job_status' => 'required',
            'working_sector' => 'required',
            'profession' => 'required',
            'phone' => 'required',

            'profile_id' => 'required|exists:profiles,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);


        $profile->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Save to public/uploads/profiles
                $image->move(public_path('uploads/profiles'), $imageName);

                Gallery::create([
                    'profile_id' => $request->profile_id,
                    'image_path' => $imageName, // store just the file name
                ]);
            }
        }


        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
