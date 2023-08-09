@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Advertisements')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('ads.create') }}" class="btn btn-circle btn-primary">
                <span>Add New Advertisement</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Advertisement Reports') }}</h5>
        </div>
        <div class="col-md-3">
            <form class="" id="sort_members" action="" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Advertisement Title')}}</th>
                    <th>{{translate('Created By')}}</th>
                    <th class="text-right">{{translate('Option')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ads as $key => $ad)
                    <tr>
                        <td>{{ ($key+1) + ($ads->currentPage() - 1)*$ads->perPage() }}</td>
                        <td>{{ $ad->title }}</td>
                        <td>{{ $ad->user->first_name . ' ' . $ad->user->last_name }}</td>
                        <td class="text-right">
                            <div class="btn-group mb-2">
                                <div class="btn-group">
                                    <button type="button" class="btn py-0" data-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <a class="dropdown-item d-none" href="{{ route('ads.show', $ad) }}">View</a>
                                        <a class="dropdown-item"
                                            href="{{ route('ads.edit', $ad) }}">Edit</a>
                                        <a class="dropdown-item"
                                            href="{{ route('ads.delete', $ad) }}">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $ads->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    <div class="modal fade member-block-modal" id="modal-basic">
    	<div class="modal-dialog">
    		<div class="modal-content">
                <form class="form-horizontal member-block" action="{{ route('members.block') }}" method="POST">
                    @csrf
                    <input type="hidden" name="member_id" id="member_id" value="">
                    <input type="hidden" name="block_status" id="block_status" value="">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Member Block !')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Blocking Reason')}}</label>
                            <div class="col-md-9">
                                <textarea type="text" name="blocking_reason" class="form-control" placeholder="{{translate('Blocking Reason')}}" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Close')}}</button>
                        <button type="submit" class="btn btn-sm btn-success">{{translate('Submit')}}</button>
                    </div>
                </form>
        	</div>
    	</div>
    </div>

    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
     function block_member(id){
         $('.member-block-modal').modal('show');
         $('#member_id').val(id);
         $('#block_status').val(1);
     }
</script>
@endsection
