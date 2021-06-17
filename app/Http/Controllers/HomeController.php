<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotification;
use App\Models\User;
use App\Notifications\SendMessage;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->userRepo = $userRepo;
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
        return view('notify');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|numeric',
        ]);

        $user = $this->userRepo->storeUser(new User($request->all()), $request->user());
        return response()->json($user);
    }

    public function notify(Request $request)
    {
        $request->validate([
            'requiredUser' => 'required',
            'deleting' => 'required|in:0,1',
            'activeUsers' => 'required|string|regex:/^\d(?:,\d)*$/'
        ]);

        $exclude_users_ids = array_merge(
            explode(',', $request->get('activeUsers')),
            [$request->get('requiredUser')['id']]
        );

        SendNotification::dispatch(
            $request->get('requiredUser'),
            UserRepository::getUsers($exclude_users_ids),
            (int) $request->get('deleting') === 1
        );

        return response()->json(['success' => true]);
    }
}
