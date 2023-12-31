<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_staff_roles'])->only('index');
        $this->middleware(['permission:add_staff_roles'])->only('create');
        $this->middleware(['permission:edit_staff_roles'])->only('edit');
        $this->middleware(['permission:delete_staff_roles'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.staff.roles.index', compact('roles'));
    }

    public function add_permission(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'parent'=> $request->parent]);
        return redirect()->route('roles.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('admin.staff.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        flash(translate('New Role has been added successfully'))->success();
        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $role = Role::findOrFail(decrypt($id));
        return view('admin.staff.roles.edit', compact('role'));
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
        // echo $request->name; die();
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->syncPermissions($request->permissions);
        $role->save();
        flash(translate('Role has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Role::destroy($id)){
            flash(translate('Role has been deleted successfully'))->success();
            return redirect()->route('roles.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
