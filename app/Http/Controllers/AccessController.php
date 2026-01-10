<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessPin;

class AccessController extends Controller
{
    // INDEX ACCESS
    public function index()
    {
        return view('admin.access');
    }

    // CHECK ACCESS
    public function store(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6'
        ]);

        $pin = AccessPin::where('pin', $request->pin)
            ->where('is_active', true)
            ->first();

        if (!$pin) {
            return redirect()->route('access.index')->with('error', 'PIN tidak valid');
        }

        session(['access_pin' => $request->pin]);

        return redirect()->route('parents.index');
    }
}