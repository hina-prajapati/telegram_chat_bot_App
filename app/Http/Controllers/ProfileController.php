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
        $preference = Preference::where('profile_id', $id)->first();

        // $telegramUserId = request()->get('chat_id');

        // if ($profile->telegram_user_id != $telegramUserId) {
        //     abort(403, 'Unauthorized access.');
        // }

        $states = DB::table('states')->get();

        $state = DB::table('states')->where('name', $profile->state)->first();

        $cities = [];

        if ($state) {
            $cities = DB::table('cities')->where('state_id', $state->id)->get();
        }

        $casts = DB::table('casts')->get();

        $caste = DB::table('casts')->where('caste_name', $profile->caste)->first();

        // dd($caste);
        $subcasts = [];

        if ($caste) {
            $subcasts = DB::table('subcasts')->where('caste_id', $caste->caste_id)->get();
        }


        $profession_categories = DB::table('profession_categories')->get();

        $specific_profession =  DB::table('profession_categories')->where('name', $profile->profession)->first();

        // dd($specific_profession);
        return view('profiles.edit', compact('profile', 'preference', 'states', 'cities', 'casts', 'subcasts', 'profession_categories'));
    }


    public function update(Request $request, $id)
    {
        $profile = Profile::with('preference')->findOrFail($id);
        // dd($profile);
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
            'income_range' => 'required',
            'profile_id' => 'nullable|exists:profiles,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'diet' => 'required',
            'smoking' => 'required',
            'drinking' => 'required',
            'body_type' => 'required',
            'skin_tone' => 'required',
            'gender' => 'required',
            'height' => 'required',
            'partner_marital_status' => 'required',
            'partner_caste' => 'required',
            'partner_min_age' => 'required',
            'partner_max_age' => 'required',
            'partner_max_height' => 'required',
            'partner_min_height' => 'required',
            'partner_religion' => 'required',
            'partner_job_status' => 'required',
            'partner_language' => 'required',
            'partner_income_range' => 'required',
            'caste' => 'required',
            'sub_caste' => 'required',
            'profession' => 'required'
        ]);
        // Get the names from their IDs
      $stateName = DB::table('states')->where('id', $request->state)->value('name');
        $cityName = DB::table('cities')->where('id', $request->city)->value('name');

        $profile->update($validated);

        $preferenceData = [
            'partner_marital_status' => $validated['partner_marital_status'],
            'partner_caste' => $validated['partner_caste'],
            'partner_min_age' => $validated['partner_min_age'],
            'partner_max_age' => $validated['partner_max_age'],
            'partner_max_height' => $validated['partner_max_height'],
            'partner_min_height' => $validated['partner_min_height'],
            'partner_religion' => $validated['partner_religion'],
            'partner_job_status' => $validated['partner_job_status'],
            'partner_language' => $validated['partner_language'],
            'partner_income_range' => $validated['partner_income_range'],
        ];

        $profile->preference()->updateOrCreate(
            ['profile_id' => $profile->id],
            $preferenceData
        );

       // Save names into profile
        $profile->state = $stateName;
        $profile->city = $cityName;
        $profile->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('uploads/profiles'), $imageName);
                Gallery::create([
                    'profile_id' => $request->profile_id,
                    'image_path' => $imageName,
                ]);
            }
        }

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgID) {
                $galleryImage = Gallery::find($imgID);
        
                $photoPath = public_path('uploads/profiles/' . $galleryImage->image_path);
        
                if ($photoPath && file_exists($photoPath)) {
                    unlink($photoPath);
                }
        
                $galleryImage->delete(); 
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function getCities($state_id)
    {
        $cities = DB::table('cities')->where('state_id', $state_id)->get();
        return response()->json($cities);
    }

}
