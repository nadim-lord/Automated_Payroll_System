<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TimeEntry;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('username');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();
        $timeEntries = TimeEntry::with('user')
                        ->when($user->position != 'Admin', function ($query) use ($user) {
                            return $query->where('user_id', $user->id);
                        })
                        ->get();

        return view('time.index', compact('timeEntries', 'user'));
    }

    public function clockIn(Request $request)
    {
        $user = $request->session()->get('username');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();

        // Check if already clocked in
        $existingEntry = TimeEntry::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();
        if ($existingEntry) {
            return redirect()->back()->with('error', 'You are already clocked in.');
        }

        $now = Carbon::now('UTC'); // Store in UTC

        TimeEntry::create([
            'user_id' => $user->id,
            'clock_in' => $now,
        ]);

        return redirect()->back()->with('success', 'Clocked in successfully at ' . $now->setTimezone('Asia/Dhaka')->format('Y-m-d h:i A'));
    }

    public function clockOut(Request $request)
    {
        $user = $request->session()->get('username');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();

        // Fetch the latest entry with null clock_out for the current user
        $timeEntry = TimeEntry::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->latest()
            ->first();

        if ($timeEntry) {
            $now = Carbon::now('UTC'); // Store in UTC
            $timeEntry->update(['clock_out' => $now]);

            return redirect()->back()->with('success', 'Clocked out successfully at ' . $now->setTimezone('Asia/Dhaka')->format('Y-m-d h:i A'));
        }

        return redirect()->back()->with('error', 'No active clock-in found');
    }

    public function update(Request $request, $id)
    {
        $user = $request->session()->get('username');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();
        $timeEntry = TimeEntry::find($id);

        if ($user->position != 'Admin' && $timeEntry->user_id != $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Update clock in and clock out fields
        $timeEntry->update([
            'clock_in' => Carbon::parse($request->clock_in, 'Asia/Dhaka')->setTimezone('UTC'),
            'clock_out' => $request->clock_out ? Carbon::parse($request->clock_out, 'Asia/Dhaka')->setTimezone('UTC') : null,
        ]);

        return redirect()->back()->with('success', 'Time entry updated successfully');
    }

    public function destroy($id)
    {
        $user = session('username');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();
        $timeEntry = TimeEntry::find($id);

        if ($user->position != 'Admin' && $timeEntry->user_id != $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $timeEntry->delete();

        return redirect()->back()->with('success', 'Time entry deleted successfully');
    }
}

