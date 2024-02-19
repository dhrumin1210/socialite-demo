<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('facebook')->user();
        $existingUser = User::where('fb_id', $user->id)->first();
        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->route('dashboard');
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'fb_id' => $user->id,
                'password' => Hash::make('123456789'),
            ]);
            Auth::login($newUser);
            return redirect()->route('dashboard');
        }


        // $facebookUser = Socialite::driver('facebook')->user();
        // dd($facebookUser);
        // try {

        //     // Find or create user based on Facebook ID
        //     $user = User::firstOrCreate([
        //         'fb_id' => $facebookUser->id,
        //     ], [
        //         'name' => $facebookUser->name,
        //         'email' => $facebookUser->email,
        //         'password' => bcrypt('admin@123'), // Use bcrypt for secure password hashing
        //     ]);

        //     // Log in the user
        //     Auth::login($user);

        //     // Redirect to dashboard or intended page
        //     return redirect('/dashboard');
        // } catch (Exception $exception) {

        //     // Handle any errors or exceptions gracefully
        //     dd($exception->getMessage());
        // }
    }
}
