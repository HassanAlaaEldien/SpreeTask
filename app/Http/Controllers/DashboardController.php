<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['index']);
    }


    /**
     *  Handle the request for viewing dashboard home page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $news = News::where('approved', true)->get();

        return view('admin.welcome', compact('news'));
    }


    /**
     *  Handle the request for viewing dashboard Login page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginPage()
    {
        return Auth::check()
            ? redirect()->route('home')
            : view('admin.login');
    }

    /**
     * Handle the request for login operation.
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return redirect()->back()->with(['fail' => 'invalid credentials!']);
        }

        return redirect()->route('home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
