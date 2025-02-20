<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReportsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $reports = Report::where('user_id', $user->id)->get(); 
        return view('dashboard', compact('user', 'reports'));
    }

    public function index()
    {
        $user = User::find(Auth::user()->id);

        if ($user->hasRole('admin')) {
            $reports = Report::with('user')->get();
        }
        elseif ($user->hasRole('manager')) {
            $reports = Report::whereHas('user', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->with('user')->get();
        } 
        else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('reports.reports-dashboard', compact('reports'));
    }

    // Menyimpan laporan baru dari user
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Report::create([
            'user_id' => Auth::id(), 
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil ditambahkan');
    }
}
