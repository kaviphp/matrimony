<!-- Mahr Give -->
<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Mahr Will Give')}}</h5>
    <div class="text-right">
        <a onclick="mahrGive_add_modal('{{$member->id}}');"  href="javascript:void(0);" class="btn btn-sm btn-primary ">
          <i class="las mr-1 la-plus"></i>
          {{translate('Add New')}}
        </a>
    </div>
</div>
<table class="table">
    <tr>
        <th>{{translate('Title')}}</th>
        <th>{{translate('Description')}}</th>
        <th class="text-right">{{translate('option')}}</th>
    </tr>

    @php $mahrGives = \App\Models\MahrGive::where([
        'user_id'=>$member->id
    ])->get(); @endphp
    @foreach ($mahrGives as $key => $mahrGive)
    <tr>
        <td>{{ $mahrGive->title }}</td>
        <td>{{ $mahrGive->description }}</td>
        <td class="text-right">
            <a href="javascript:void(0);" class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="mahrGive_edit_modal('{{$mahrGive->id}}');" title="{{ translate('Edit') }}">
                <i class="las la-edit"></i>
            </a>
            <a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('mahr-give.destroy', $mahrGive->id)}}" title="{{ translate('Delete') }}">
                <i class="las la-trash"></i>
            </a>
        </td>
    </tr>
    @endforeach

</table>

