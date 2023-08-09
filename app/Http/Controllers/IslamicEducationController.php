<?php

namespace App\Http\Controllers;

use App\Models\IslamicEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class IslamicEducationController extends Controller
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
        $islamic_educations = IslamicEducation::latest();

        if ($request->has('search')){
            $sort_search    = $request->search;
            $islamic_educations  = $islamic_educations->where('name', 'like', '%'.$sort_search.'%');
        }
        $islamic_educations = $islamic_educations->paginate(10);
        return view(
            'admin.member_profile_attributes.islamic_education.index',
            compact('islamic_educations','sort_search')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $islamic_education       = new IslamicEducation;
        $islamic_education->name = $request->name;
        if($islamic_education->save()){
            flash(translate('New islamic education has been added successfully'))->success();
            return redirect()->route('islamic-education.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IslamicEducation  $islamicEducation
     * @return \Illuminate\Http\Response
     */
    public function show(IslamicEducation $islamicEducation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\View\View The view for the family values index page.
     */
    public function edit($id)
    {
        $islamic_education   = IslamicEducation::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.islamic_education.edit', compact('islamic_education'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IslamicEducation  $islamicEducation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, IslamicEducation $islamicEducation)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $islamicEducation->name = $request->name;
        if($islamicEducation->save()){
            flash(translate('Islamic education has been updated successfully'))->success();
            return redirect()->route('islamic-education.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (IslamicEducation::destroy($id)) {
            flash(translate('Islamic education has been deleted successfully'))->success();
            return redirect()->route('islamic-education.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
