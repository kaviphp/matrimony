@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Advertisment')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('ads.update', $ad) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="title">
                            {{translate('Advertisment Title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Advertisment Title')}}" id="title"
                                name="title" class="form-control" required value="{{ $ad->title }}">
                        </div>
                    </div>
                    <div class="form-group row" id="category">
                        <label class="col-md-3 col-from-label" for="page_id">
                            {{translate('Pages')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="page_id[]" id="page_id"
                                data-live-search="true" multiple required>
                                <option value="" disabled>{{ translate('Select Pages') }}</option>
                                @foreach ($pages as $page)
                                    @if ($ad->advertisementPages->pluck('id')->contains($page->id))
                                    <option value="{{ $page->id }}" selected>{{ $page->name }}</option>
                                    @else
                                    <option value="{{ $page->id }}">{{ $page->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Description')}}
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <textarea name="description" id="description" cols="30" rows="10"
                                class="form-control" required>{{ $ad->description }}</textarea>
                        </div>
                    </div>

                    @foreach ($ad->images as $image)
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="link1">
                            {{translate('Link One')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="url" placeholder="{{translate('Link One')}}"
                                id="link1" name="url[]" class="form-control" required
                                value="{{ $image->url }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">
                            {{translate('Image One')}}
                            <small>(1300x650)</small>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="images[]" class="selected-files" required
                                    value="{{ $image->image }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="form-group mb-0 text-right">
                        <a href="{{ route('ads.index') }}" class="btn btn-info">
                            {{translate('Back')}}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{translate('Update')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
