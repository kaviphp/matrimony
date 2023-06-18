<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function store(array $data)
    {
        $collection = collect($data);
        $password = Hash::make($data['password']);
        $code = unique_code();
        $approval = get_setting('member_approval_by_admin') == 1 ? 0 : 1;
        $approved = $approval;
        $membership = 1;
        $verification_code = rand(100000, 999999);
        $referred_by = null;
        
        if (addon_activation('referral_system')) {
            $reffered_user = User::where('code', '!=', null)->where('code', $data['referral_code'])->first();
            if ($reffered_user) {
                $referred_by = $reffered_user->id;
            }
            $data = $collection->merge(compact('referred_by'))->toArray();
        }

        if (addon_activation('otp_system') && isset($data['phone'])) {
            $data = $collection->merge(compact('password', 'code', 'approved', 'membership', 'phone', 'verification_code', 'referred_by'))->toArray();
        }else{
          $data = $collection->merge(compact('password', 'code', 'approved', 'membership','verification_code', 'referred_by'))->toArray();
        }
        return User::create($data);
    }
}
