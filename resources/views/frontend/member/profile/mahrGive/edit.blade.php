<form action="{{ route('mahr-give.update', $mahrGive->id) }}" method="POST">
    <input name="_method" type="hidden" value="PATCH">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Edit Mahr Will Give')}}</h5>
        <button type="button" class="close" data-dismiss="modal">
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Title')}}</label>
            <div class="col-md-9">
                <input type="text" name="title" class="form-control" value="{{$mahrGive->title}}" placeholder="{{translate('Title')}}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Description')}}</label>
            <div class="col-md-9">
                <input type="text" name="description"  placeholder="{{ translate('Description') }}" value="{{$mahrGive->description}}" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Edit Mahr Will Give')}}</button>
    </div>
</form>
