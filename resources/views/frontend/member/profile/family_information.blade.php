<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Family Information')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('families.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="father">{{translate('Father')}}</label>
                    <input type="text" name="father" value="{{ !empty($member->families->father) ? $member->families->father : "" }}" class="form-control" placeholder="{{translate('Father')}}" required>
                    @error('father')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="mother">{{translate('Mother')}}</label>
                    <input type="text" name="mother" value="{{ !empty($member->families->mother) ? $member->families->mother : "" }}" placeholder="{{ translate('Mother') }}" class="form-control" required>
                    @error('mother')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="sibling">{{translate('Sibling')}}</label>
                    <input type="text" name="sibling" value="{{ !empty($member->families->sibling) ? $member->families->sibling : "" }}" class="form-control" placeholder="{{translate('Sibling')}}" required>
                    @error('sibling')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="no_of_brothers">{{translate('No.Of Brothers')}}</label>
                    <input type="text" name="no_of_brothers" value="{{ !empty($member->families->no_of_brothers) ? $member->families->no_of_brothers : "" }}" class="form-control" placeholder="{{translate('No.Of Brothers')}}" required>
                    @error('no_of_brothers')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="no_of_sisters">{{translate('No.Of sisters')}}</label>
                    <input type="text" name="no_of_sisters" value="{{ !empty($member->families->no_of_sisters) ? $member->families->no_of_sisters : "" }}" class="form-control" placeholder="{{translate('No.Of Sisters')}}" required>
                    @error('no_of_sisters')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="religion_id">{{translate('Religiousness')}}</label>
                    <select class="form-control aiz-selectpicker" name="religion_id" id="religion_id" data-live-search="true" required>
                        <option value="">{{translate('Select One')}}</option>
                        @foreach ($religions as $religion)
                            <option value="{{$religion->id}}" @if($religion->id == $member->families?->religion_id) selected @endif> {{ $religion->name }} </option>
                        @endforeach
                    </select>
                    @error('religion_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="family_status">{{translate('Family Status')}}</label>
                    <select class="form-control aiz-selectpicker" name="family_status" id="family_status" data-live-search="true" required>
                        <option value="">{{translate('Select One')}}</option>
                        @foreach ($family_statuses as $family_status)
                            <option value="{{$family_status->id}}" @if($family_status->id == $member->families?->family_status_id) selected @endif> {{ $family_status->name }} </option>
                        @endforeach
                    </select>
                    @error('family_status')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="family_value">{{translate('Family Type')}}</label>
                    <select class="form-control aiz-selectpicker" name="family_value" id="family_value" data-live-search="true" required>
                        <option value="">{{translate('Select One')}}</option>
                        @foreach ($family_values as $family_value)
                            <option value="{{$family_value->id}}" @if($family_value->id == $member->families?->family_value_id) selected @endif> {{ $family_value->name }} </option>
                        @endforeach
                    </select>
                    @error('family_value')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="parent_ph_no">{{translate('Parent Phone Number')}}</label>
                    <input type="text" name="parent_ph_no" value="{{ !empty($member->families->parent_ph_no) ? $member->families->parent_ph_no : "" }}" class="form-control" placeholder="{{translate('Parent Phone Number')}}" required>
                    @error('parent_ph_no')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
            </div>
        </form>
    </div>
</div>
