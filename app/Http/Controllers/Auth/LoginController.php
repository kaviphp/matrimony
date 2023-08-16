<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Member;
use App\Models\User;
use Socialite;
use CoreComponentRepository;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            flash("Something Went wrong. Please try again.")->error();
            return redirect()->route('user.login');
        }
        // check if they're an existing user
        $existingUser = User::where('provider_id', $user->id)->orWhere('email', $user->email)->first();

        if ($existingUser) {
            // log them in
            if($existingUser->approved == 0){
                flash("Please wait for the admin approval.")->error();
                return redirect()->route('user.login');
            } else {
                auth()->login($existingUser, true);
            }

        } else {

            // create a new user
            $newUser                     = new User;
            $newUser->first_name         = $user->name;
            $newUser->email              = $user->email;
            $newUser->email_verified_at  = date('Y-m-d H:m:s');
            $newUser->provider_id        = $user->id;
            $newUser->code               = unique_code();
            $newUser->membership         = 1;
            $newUser->approved           = get_setting('member_approval_by_admin') == 1 ? 0 : 1;
            $newUser->save();

            $member                             = new Member;
            $member->user_id                    = $newUser->id;
            $member->gender                     = null;
            $member->on_behalves_id             = null;
            $member->birthday                   = null;

            $package                                = Package::where('id', 1)->first();
            $member->current_package_id             = $package->id;
            $member->remaining_interest             = $package->express_interest;
            $member->remaining_photo_gallery        = $package->photo_gallery;

            $member->remaining_contact_view         = $package->contact;
            $member->remaining_profile_image_view    = $package->profile_image_view;
            $member->remaining_gallery_image_view   = $package->gallery_image_view;
            $member->auto_profile_match             = $package->auto_profile_match;
            $member->package_validity               = Date('Y-m-d', strtotime($package->validity . " days"));
            $member->save();

            if($newUser->approved == 0){
                flash("Please wait for the admin approval.")->error();
                return redirect()->route('user.login');
            } else {
                auth()->login($existingUser, true);
            }
        }
        if (session('link') != null) {
            return redirect(session('link'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
        $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            $checkVerify = User::where(function ($query) use ($request) {
                $query->where('email', $request->email)
                      ->orWhere('phone', $request->email);
            })
            ->where('email_verified_at', null)
            ->first();

            if (
                !empty($checkVerify) &&
                Hash::check(
                    $request->password,
                    $checkVerify->password
                    )
                ) {
                return redirect()->route('user.verify');
            }

            return $this->sendLoginResponse($request);
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return $request->only($this->username(), 'password');
        } else {
            return ['phone' => $request->get('email'), 'password' => $request->get('password')];
        }
    }

    public function authenticated()
    {
        activity()->causedBy(auth()->user())->log('User logged in');
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
            return redirect()->route('admin.dashboard');
        } else {
            if (session('link') != null) {
                return redirect(session('link'));
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function showLoginForm()
    {
        return view('frontend.user_login');
    }

    public function logout(Request $request)
    {
        if (auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')) {
            $redirect_route = 'admin';
        } else {
            $redirect_route = 'home';
        }
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }
}
