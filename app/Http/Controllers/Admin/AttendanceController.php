<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $attendances = Attendance::with('user')->orderByDesc('date')->paginate(15);
        $staff = User::orderBy('name')->get();

        return view('admin.attendances.index', compact('attendances', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,sick,leave,absent',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            ['user_id' => $validated['user_id'], 'date' => $validated['date']],
            $validated + ['latitude_in' => $request->latitude_in, 'longitude_in' => $request->longitude_in]
        );
        ActivityLog::log('created', 'absensi', "Mencatat absensi user #{$validated['user_id']} pada {$validated['date']}");

        return redirect()->route('admin.attendances.index')->with('success', 'Data absensi disimpan.');
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $request->validate(['latitude' => 'nullable|numeric', 'longitude' => 'nullable|numeric', 'photo' => 'nullable|image|max:2048']);
        $photo = $request->hasFile('photo') ? $request->file('photo')->store('attendances', 'public') : null;

        Attendance::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => today()->toDateString()],
            [
                'clock_in' => now()->format('H:i:s'),
                'status' => now()->hour >= 8 ? 'late' : 'present',
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'photo_in' => $photo,
            ],
        );

        return back()->with('success', 'Check-in / Presensi berhasil.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $request->validate(['latitude' => 'nullable|numeric', 'longitude' => 'nullable|numeric', 'photo' => 'nullable|image|max:2048']);
        $photo = $request->hasFile('photo') ? $request->file('photo')->store('attendances', 'public') : null;

        $attendance = Attendance::where('user_id', auth()->id())->where('date', today()->toDateString())->firstOrFail();
        $attendance->update([
            'clock_out' => now()->format('H:i:s'),
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
            'photo_out' => $photo,
        ]);

        return back()->with('success', 'Check-out berhasil.');
    }
}
