<?php

namespace App\Http\Controllers;

use App\Models\MassSchedule;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ScheduleController extends Controller
{
    public function index()
{
    $schedules = MassSchedule::orderByRaw('CAST(day_of_week AS CHAR)')
        ->orderByRaw('CAST(time AS CHAR)')
        ->get();

    return view('admin.schedules.index', compact('schedules'));
}

    public function show(MassSchedule $schedule)
    {
        return view('admin.schedules.show', compact('schedule'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'mass_type' => 'required|string',
            'day_of_week' => 'nullable|array',
            'day_of_week.*' => 'string',
            'time' => 'required|array',
            'time.*' => 'string',
            'location' => 'nullable|string',
        ]);

        MassSchedule::create($validated);
        Cache::forget('chatbot_parish_context');
        Cache::forget('public_mass_schedules');
        LogService::log('create_schedule', null, ['mass_type' => $validated['mass_type'], 'day_of_week' => $validated['day_of_week'] ?? null, 'time' => $validated['time'] ?? null]);

        return $this->redirectOrJson($request, 'admin.schedules.index', 'Schedule created.');
    }

    public function edit(MassSchedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, MassSchedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'mass_type' => 'required|string',
            'day_of_week' => 'nullable|array',
            'day_of_week.*' => 'string',
            'time' => 'required|array',
            'time.*' => 'string',
            'location' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $schedule->update($validated);
        Cache::forget('chatbot_parish_context');
        Cache::forget('public_mass_schedules');
        LogService::log('update_schedule', $schedule, ['mass_type' => $validated['mass_type'] ?? null]);

        return $this->redirectOrJson($request, 'admin.schedules.index', 'Schedule updated.');
    }

    public function destroy(MassSchedule $schedule)
    {
        LogService::log('delete_schedule', $schedule, ['mass_type' => $schedule->mass_type]);
        Cache::forget('chatbot_parish_context');
        Cache::forget('public_mass_schedules');
        $schedule->delete();

        return back()->with('success', 'Schedule deleted.');
    }
}
