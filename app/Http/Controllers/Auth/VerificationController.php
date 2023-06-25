<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Http\Controllers\OTPVerificationController;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return view('frontend.user_verify');
    }

    public function resend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('phone', $request->phone)
            ->orWhere('email', $request->phone)
            ->first();

        if (!$user) {
            flash(translate('Sorry, account not found. Please try again'))->error();
            return back();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $otp = rand(100000, 999999);
        $user->verification_code = $otp;
        $user->save();

        $message = "Your " . get_setting('site_name') . " OTP is $otp";
        $to = $user->phone;
        sendSms($message, $to);

        return back()->with('resent', true);
    }

    public function verification_confirmation(Request $request){
        $user = User::where('verification_code', $request->otp)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->orWhere('phone', $request->email);
            })
            ->first();
        
        if($user != null){
            $user->email_verified_at = Carbon::now();
            $user->save();
            auth()->login($user, true);
            flash(translate('Your email has been verified successfully'))->success();
        }
        else {
            flash(translate('Sorry, we could not verify you. Please try again'))->error();
        }

        return redirect()->route('dashboard');
    }
}
