<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getSetting(): View|Application|Factory
    {
        return view('/settings/settings-dashboard');
    }
}
