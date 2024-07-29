<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class WelcomeController
 * @package App\Http\Controllers
 */
class WelcomeController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (auth()->check())
            return redirect()->route('app.dashboard');

        return redirect()->route('login');
    }
}
