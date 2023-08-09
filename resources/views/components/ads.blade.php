@foreach ($ads as $ad)
<section class="bg-white">
    <div class="container">
        <div class="row gutters-10">

            @foreach ($ad->images as $key => $image)
                <div class="col-xl col-md-6">
                    <div class="mb-3">
                        <a href="{{ $image->url }}" class="d-block text-reset"
                            target="_blank">
                            @if(uploaded_asset($image->image) != null)
                                <img class="img-fluid lazyload w-100"
                                    src="{{ uploaded_asset($image->image) }}"
                                    height="45px" alt="{{translate('photo')}}">
                            @else
                                <img class="img-fluid lazyload w-100" src="{{
                                        static_asset('assets/img/placeholder-rect.jpg')
                                    }}"
                                    height="45px" alt="{{translate('photo')}}">
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach
