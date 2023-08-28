<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family;
use Validator;
use Redirect;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
         $this->rules = [
             'father'   => [ 'max:255'],
             'mother'   => [ 'max:255'],
             'sibling'  => [ 'max:255'],
             'no_of_brothers'  => ['numeric'],
             'no_of_sisters'  => ['numeric'],
             'religion_id'  => ['numeric'],
             'family_status'  => ['numeric'],
             'family_value'  => ['numeric'],
             'parent_ph_no' => ['nullable']
         ];
         $this->messages = [
             'father.max'   => translate('Max 255 characters'),
             'mother.max'   => translate('Max 255 characters'),
             'sibling.max'  => translate('Max 255 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $family = Family::where('user_id', $id)->first();
         if(empty($family)){
             $family           = new Family;
             $family->user_id  = $id;
         }

         $family->father    = $request->father;
         $family->mother    = $request->mother;
         $family->sibling   = $request->sibling;
         $family->no_of_brothers   = $request->no_of_brothers;
         $family->no_of_sisters   = $request->no_of_sisters;
         $family->religion_id   = $request->religion_id;
         $family->family_status_id   = $request->family_status;
         $family->family_value_id   = $request->family_value;
         $family->parent_ph_no   = $request->parent_ph_no;

         if($family->save()){
            activity()->causedBy(auth()->user())->log('User family Info has been updated.');
             flash(translate('Family info has been updated successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }

     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
