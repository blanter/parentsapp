<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebSetting;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = WebSetting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            WebSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
