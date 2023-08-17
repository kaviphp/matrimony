<form action="{{ route('mahr-give.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Add New Mahr Will Give')}}</h5>
        <button type="button" class="close" data-dismiss="modal">
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="user_id" value="{{ $member_id }}">
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Title')}}</label>
            <div class="col-md-9">
                <input type="text" name="title" class="form-control" placeholder="{{translate('Title')}}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Description')}}</label>
            <div class="col-md-9">
                <input type="text" name="description"  placeholder="{{ translate('Description') }}" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Add New Mahr Will Give')}}</button>
    </div>
</form>
