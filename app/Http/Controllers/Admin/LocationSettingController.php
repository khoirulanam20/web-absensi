<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationSetting;
use Illuminate\Http\Request;

class LocationSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.location-settings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.location-settings.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $locationSetting = LocationSetting::findOrFail($id);
        return view('admin.location-settings.edit', ['locationSetting' => $locationSetting]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
        ]);

        try {
            LocationSetting::create([
                'name' => $request->name,
                'latitude' => doubleval($request->latitude),
                'longitude' => doubleval($request->longitude),
                'radius' => intval($request->radius),
                'is_active' => $request->has('is_active'),
            ]);
            return redirect()->route('admin.location-settings')
                ->with('flash.banner', __('Created successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('flash.banner', $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
        ]);

        try {
            $locationSetting = LocationSetting::findOrFail($id);
            $locationSetting->update([
                'name' => $request->name,
                'latitude' => doubleval($request->latitude),
                'longitude' => doubleval($request->longitude),
                'radius' => intval($request->radius),
                'is_active' => $request->has('is_active'),
            ]);
            return redirect()->route('admin.location-settings')
                ->with('flash.banner', __('Updated successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('flash.banner', $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $locationSetting = LocationSetting::findOrFail($id);
            $locationSetting->delete();
            return redirect()->route('admin.location-settings')
                ->with('flash.banner', __('Deleted successfully.'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('flash.banner', $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }
}
