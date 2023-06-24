<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Member;
use App\Models\Package;
use App\Models\EmailTemplate;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Notification;
use App\Notifications\DbStoreNotification;
use App\Utility\EmailUtility;
use Kutia\Larafirebase\Facades\Larafirebase;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        //
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('frontend.user_registration');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'  => ['required', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'phone'       => ['required', 'regex:/^[6-9]\d{9}$/'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'phone.regex' => 'Please enter a valid phone number.',
            'email.unique' => 'This email is already registered.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $approval = get_setting('member_approval_by_admin') == 1 ? 0 : 1;
        $user = User::create([
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'membership'  => 1,
            'email'       => $data['email'],
            'phone'       => '+' . $data['country_code'] . $data['phone'],
            'password'    => Hash::make($data['password']),
            'code'        => unique_code(),
            'approved'    => $approval,
            'verification_code' => rand(100000, 999999)
        ]);
        if (addon_activation('referral_system') && $data['referral_code'] != null) {
            $reffered_user = User::where('code', '!=', null)->where('code', $data['referral_code'])->first();
            if ($reffered_user != null) {
                $user->referred_by = $reffered_user->id;
                $user->save();
            }
        }

        $member                             = new Member;
        $member->user_id                    = $user->id;
        $member->save();

        $member->gender                     = $data['gender'];
        $member->on_behalves_id             = $data['on_behalf'];
        $member->birthday                   = date('Y-m-d', strtotime($data['date_of_birth']));

        $package                                = Package::where('id', 1)->first();
        $member->current_package_id             = $package->id;
        $member->remaining_interest             = $package->express_interest;
        $member->remaining_photo_gallery        = $package->photo_gallery;
        $member->remaining_contact_view         = $package->contact;
        $member->remaining_profile_image_view   = $package->profile_image_view;
        $member->remaining_gallery_image_view   = $package->gallery_image_view;
        $member->auto_profile_match             = $package->auto_profile_match;
        $member->package_validity               = Date('Y-m-d', strtotime($package->validity . " days"));
        $member->save();


        // Account opening Email to member
        // if ($data['email'] != null  && env('MAIL_USERNAME') != null) {
        //     $account_oppening_email = EmailTemplate::where('identifier', 'account_oppening_email')->first();
        //     if ($account_oppening_email->status == 1) {
        //         EmailUtility::account_oppening_email($user->id, $data['password']);
        //     }
        // }

        return $user;
    }

    public function register(Request $request)
    {
        if (User::where('phone', '+' . $request->country_code . $request->phone)->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        if (get_setting('member_approval_by_admin') != 1) {
            $this->guard()->login($user);
        }

        try {
            $notify_type = 'member_registration';
            $id = unique_notify_id();
            $notify_by = $user->id;
            $info_id = $user->id;
            $message = translate('A new member has been registered to your system. Name: ') . $user->first_name . ' ' . $user->last_name;
            $route = route('members.index', $user->membership);

            // fcm
            if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('user_type', 'admin')->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
            }
            // end of fcm
            $users = User::where('user_type', 'staff')->get();
            foreach ($users as $staff) {
                Notification::send($staff, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            }
        } catch (\Exception $e) {
            // dd($e);
        }
        if (env('MAIL_USERNAME') != null && (get_email_template('account_opening_email_to_admin', 'status') == 1)) {
            $admins = User::where('user_type', 'staff')->get();
            foreach ($admins as $admin) {
                EmailUtility::account_opening_email_to_admin($user, $admin);
            }
        }

        if ($user->phone != null) {
            flash(translate('Registration successfull. Please verify your phone number.'))->success();
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        //?? where should redirect user after registration
        if (get_setting('member_approval_by_admin') == 1) {
            $otp = rand(100000, 999999);
            $user->verification_code = $otp;
            $user->save();

            $message = "Your " . get_setting('site_name') . " OTP is $otp";
            $to = $user->phone;
            sendSms($message, $to);

            return redirect()->route('user.verify');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
