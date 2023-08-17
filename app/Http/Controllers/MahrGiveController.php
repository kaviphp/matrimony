<?php

namespace App\Http\Controllers;

use App\Models\MahrGive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahrGiveController extends Controller
{
    /**
     * Retrieves the list of family values for admin member profile attributes.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View The view for the family values index page.
     */
    public function index(Request $request)
    {
        $sort_search   = null;
        $mahrGives = MahrGive::latest();

        if ($request->has('search')){
            $sort_search    = $request->search;
            $mahrGives  = $mahrGives->where('title', 'like', '%'.$sort_search.'%');
        }
        $mahrGives = $mahrGives->paginate(10);
        return view(
            'admin.member_profile_attributes.mahr_give.index',
            compact('mahrGives','sort_search')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $member_id = $request->id;
        return view('frontend.member.profile.mahrGive.create', compact('member_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'title' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return back()->withErrors($validator);
        }

        $islamic_education       = new MahrGive;
        $islamic_education->user_id = $request->user_id;
        $islamic_education->title = $request->title;
        $islamic_education->description = $request->description;
        if($islamic_education->save()){
            activity()->causedBy(auth()->user())->log('User Mahr will give has been added.');
            flash(translate('Mahr will give has been added successfully'))->success();
            return redirect()->back();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Request $request)
    {
        $mahrGive   = MahrGive::findOrFail($request->id);
        return view('frontend.member.profile.mahrGive.edit', compact('mahrGive'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return back()->withErrors($validator);
        }

        $mahrGive              = MahrGive::findOrFail($id);
        $mahrGive->title = $request->title;
        $mahrGive->description = $request->description;
        $mahrGive->save();
        activity()->causedBy(auth()->user())->log('User Mahr will give has been updated.');
        flash(translate('Mahr will give has been updated successfully'))->success();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        MahrGive::destroy($id);
        activity()->causedBy(auth()->user())->log('User Mahr will give has been deleted.');
        flash(translate('Mahr will give has been deleted successfully'))->success();
        return redirect()->back();
    }
}
