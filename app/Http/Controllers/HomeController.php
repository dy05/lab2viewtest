<?php

namespace App\Http\Controllers;

use App\Events\NewMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        return view('notif');
    }

    public function store(Request $request)
    {
        $user = (new User($request->all()));
        if ($user->save()) {
            broadcast(new NewMember($user, $request->user()));
//            Artisan::call();
        }
    }
}
