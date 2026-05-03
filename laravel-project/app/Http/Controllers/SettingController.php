<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function profile()
    {
        $company = (object)[
            'name' => 'PT Indosaku Digital Teknologi',
            'email' => 'service@indosaku.id',
            'phone' => '+62 21 5088 0123',
            'address' => 'Ruko Spectra, Jl. Jalur Sutera Kav. 23B No. 1-2, Panunggangan Timur, Kec. Pinang, Kota Tangerang, Banten 15143',
            'website' => 'https://indosaku.id',
            'tax_id' => '92.123.456.7-412.000',
            'logo' => null
        ];

        return view('settings.profile', compact('company'));
    }

    public function updateProfile(Request $request)
    {
        // For mock, just redirect back with success
        return redirect()->back()->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function password()
    {
        return view('settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // In a real app, verify and update
        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function integrations()
    {
        $integrations = [
            'google' => [
                'name' => 'Google Ads',
                'status' => 'connected',
                'api_key' => 'AIzaSyB3_7vD9W-xG1L9k2M8p0Q4r5S6t7U8v9',
                'client_id' => '458291034567-fgh8j9k0l1m2n3o4p5q6r7s8t9u0v1.apps.googleusercontent.com',
                'last_sync' => '2025-10-31 09:00'
            ],
            'meta' => [
                'name' => 'Meta Ads (Facebook/Instagram)',
                'status' => 'connected',
                'api_key' => 'EAAGm3oP2ZB4BAHf9g0h1i2j3k4l5m6n7o8p9q0r1s2t3u4v5w6x7y8z9a0b1c2d3',
                'app_id' => '102938475610293',
                'last_sync' => '2025-10-31 08:30'
            ],
            'tiktok' => [
                'name' => 'TikTok Ads',
                'status' => 'connected',
                'api_key' => '67d9a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0',
                'app_id' => '7234567890123456789',
                'last_sync' => '2025-10-31 10:15'
            ]
        ];

        return view('settings.integrations', compact('integrations'));
    }

    public function updateIntegration(Request $request, $platform)
    {
        return redirect()->back()->with('success', "Integrasi " . ucfirst($platform) . " berhasil diperbarui.");
    }
}
