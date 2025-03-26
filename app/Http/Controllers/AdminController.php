<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;
use App\Models\SensorStatus;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function manageAdmins()
    {
        $admins = User::all(); // Get all admins
        $sensors = Sensor::all(); // Get all sensors
        $statuses = SensorStatus::all(); // Get all statuses
    
        return view('dashboard', compact('admins', 'sensors', 'statuses')); // Pass both to view
    }
    public function updateAdmin(Request $request)
    {
        // Validate the input
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $request->id
        ]);
    
        // Find the admin and update details
        $admin = User::findOrFail($request->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();
    
        return response()->json(['success' => true]);
    }

    public function destroy($id)
{
    // Check authentication
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    // Prevent self-deletion
    if ($id == Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'You cannot delete your own account'
        ], 403);
    }

    // Find the user
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    try {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting user: ' . $e->getMessage()
        ], 500);
    }
}
    
}
