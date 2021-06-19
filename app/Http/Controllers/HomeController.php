<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepo;

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

    public function index(): View
    {
        return view('home');
    }

    public function create(): View
    {
        return view('notify');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|numeric',
        ]);

        $user = $this->userRepo->storeUser(new User($request->all()), $request->user());
        return response()->json($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function notify(Request $request): JsonResponse
    {
        $request->validate([
            'uuid' => 'required'
        ]);

        $viewed = UserRepository::addViewedUser($request->get('uuid'), $request->user()->id);

        return response()->json(['success' => $viewed]);
    }
}
