@extends('admin.layouts.app')

@section('content')

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Staff Information')}}</h5>
        </div>

        <form class="form-horizontal" action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="first_name">{{translate('First Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="first_name" placeholder="{{translate('First Name')}}" id="first_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="last_name">{{translate('Last Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="last_name" placeholder="{{translate('Last Name')}}" id="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" placeholder="{{translate('Email')}}" id="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="mobile" placeholder="{{translate('Phone')}}" id="mobile" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" splaceholder="{{translate('Password')}}" id="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Role')}}</label>
                    <div class="col-sm-9">
                        <select name="role_id" required class="form-control aiz-selectpicker">
                            @foreach($roles as $role)
                                @if(auth()->user()->can('add_staff_roles'))
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @elseif($role->id != 1)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
