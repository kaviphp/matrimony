@extends('frontend.layouts.app')

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">
        <div class="row">
            <div class="col-xxl-4 col-xl-5 col-md-7 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-5 text-center">
                            <h1 class="h3 text-primary mb-0">Verify Your Account.</h1>
                        </div>

                        <form class="" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="email">
                                    {{ translate('Email/Phone') }}
                                </label>
                                @if (!addon_activation('otp_system'))
                                    <input type="text" class="form-control mb-2 {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email">
                                @endif
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @if (!addon_activation('otp_system'))
                                    <span class="opacity-60">{{ translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="otp">{{ translate('otp') }}</label>
                                <input type="otp" class="form-control @error('otp') is-invalid @enderror" name="otp" id="otp" placeholder="OTP" required>
                                @error('otp')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 text-right">
                                <a class="link-muted text-capitalize font-weight-normal" href="javascript:void(0)" id="resend-otp">
                                    Resend OTP?
                                </a>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-block btn-primary">{{ translate('veify_otp') }}</button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">{{ translate("Don't have an account?") }}</p>
                            <a href="{{ route('register') }}">{{ translate('Create an account') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('user.resend.otp') }}" method="post" id="resend-otp-form">
    @csrf
    <input type="hidden" name="phone" id="phone">
</form>
@endsection

@section('script')
<script>
    $('#resend-otp').click(function(){
        $('#phone').val($('#email').val());
        $('#resend-otp-form').submit();
    })
</script>
@endsection
