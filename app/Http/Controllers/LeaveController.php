<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LeaveController extends Controller
{
    public function index()
    {
        $user = session('username');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();
        $leaves = Leave::with('user')
                    ->when($user->position != 'Admin', function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    })
                    ->get();

        return view('leave.index', compact('leaves', 'user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = session('username');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('name', $user)->first();

        if ($user->position == 'Admin') {
            return redirect()->back()->with('error', 'Admin cannot submit leave requests');
        }

        Leave::create([
            'user_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
        ]);

        $leave = Leave::find($id);

        $leave->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Leave request updated successfully');
    }

    public function destroy($id)
    {
        $leave = Leave::find($id);
        $leave->delete();

        return redirect()->back()->with('success', 'Leave request deleted successfully');
    }
}
