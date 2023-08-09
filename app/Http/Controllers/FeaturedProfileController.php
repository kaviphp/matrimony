<?php

namespace App\Http\Controllers;

use App\Models\User;

class FeaturedProfileController extends Controller
{
    public function store(User $user)
    {
        $user->update([ 'isFeatured' => true ]);

        flash(translate('User successfully added to the featured profile'))->success();
        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $user->update([
            'isFeatured' => false
        ]);

        flash(translate('User removed from featured profile'))->success();
        return redirect()->back();
    }
}
