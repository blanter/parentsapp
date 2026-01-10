<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GardeningPlant;
use App\Models\GardeningProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GardeningController extends Controller
{
    public function index()
    {
        $plants = GardeningPlant::where('user_id', Auth::id())->latest()->get();
        return view('gardening.index', compact('plants'));
    }

    public function show($id)
    {
        $plant = GardeningPlant::where('user_id', Auth::id())->with('progress')->findOrFail($id);

        // Prepare events for calendar
        $events = $plant->progress->map(function ($p) {
            return [
                'date' => $p->report_date,
                'status' => 'filled',
                'id' => $p->id,
                'description' => $p->description,
                'image' => $p->image ? asset('gardening/progress/' . $p->image) : null
            ];
        })->keyBy('date')->toArray();

        return view('gardening.show', compact('plant', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'plant_name' => 'required|string|max:255',
            'planting_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'method' => $request->input('method'),
            'plant_name' => $request->plant_name,
            'planting_date' => $request->planting_date,
            'icon' => $request->icon,
        ];

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('gardening'), $imageName);
            $data['image'] = $imageName;
        }

        GardeningPlant::create($data);

        return back()->with('success', 'Tanaman berhasil ditambahkan!');
    }

    public function storeProgress(Request $request, $id)
    {
        $plant = GardeningPlant::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'report_date' => 'required|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ]);

        $reportDate = Carbon::parse($request->report_date);

        $data = [
            'gardening_plant_id' => $plant->id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'report_date' => $request->report_date,
            'report_month' => $reportDate->month,
            'report_year' => $reportDate->year,
            'score' => 0, // Default score
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $id . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('gardening/progress');

            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            // Standard PHP GD Compression
            $source_path = $image->getPathname();
            $target_path = $destinationPath . '/' . $imageName;

            $info = getimagesize($source_path);
            if ($info['mime'] == 'image/jpeg') {
                $image_res = imagecreatefromjpeg($source_path);
                imagejpeg($image_res, $target_path, 50); // 50% Quality
            } elseif ($info['mime'] == 'image/png') {
                $image_res = imagecreatefrompng($source_path);
                imagepng($image_res, $target_path, 5); // PNG scale is 0-9
            } else {
                $image->move($destinationPath, $imageName);
            }

            $data['image'] = $imageName;
        }

        // Update if exists on same date, otherwise create
        GardeningProgress::updateOrCreate(
            ['gardening_plant_id' => $plant->id, 'report_date' => $request->report_date],
            $data
        );

        return back()->with('success', 'Progress berhasil disimpan!');
    }

    public function destroy($id)
    {
        $plant = GardeningPlant::where('user_id', Auth::id())->findOrFail($id);

        if ($plant->image) {
            $path = public_path('gardening/' . $plant->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $plant->delete();
        return back()->with('success', 'Tanaman berhasil dihapus!');
    }

    public function destroyProgress($id)
    {
        $progress = GardeningProgress::where('user_id', Auth::id())->findOrFail($id);

        if ($progress->image) {
            $path = public_path('gardening/progress/' . $progress->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $progress->delete();
        return back()->with('success', 'Progress berhasil dihapus!');
    }
}
