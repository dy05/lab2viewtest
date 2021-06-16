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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|numeric',
        ]);
        $user = (new User($request->all()));
//        broadcast(new NewMember($user, $request->user()));
        broadcast(new NewMember($request->user(), $request->user()));
        return response()->json($user);
        die();
        if ($user->save()) {
//            Artisan::call();
            return response()->json($user);
        }
    }
}
