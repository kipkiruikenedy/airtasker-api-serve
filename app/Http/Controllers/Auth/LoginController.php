<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check user role and redirect based on role
        switch ($user->role) {
            case 'admin':
                return '/admin/dashboard';
                break;
            case 'user':
                return '/user/dashboard';
                break;
            case 'customer':
                return '/customer/dashboard';
                break;
            default:
                return '/home';
                break;
        }
    }
}
