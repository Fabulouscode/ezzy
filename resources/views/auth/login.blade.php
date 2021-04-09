@extends('layouts.auth')

@section('title','Admin Sign In')

@section('content')
<div class="content-desc-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-content-part">
                            <h3 class="text-center mt-0 m-b-15">
                                <a href="{{ url('/') }}" class="logo logo-admin"><img src="{{ asset('admin/images/logo-dark.png')}}" height="80" alt="logo"></a>
                            </h3>

                            <h4 class="text-muted text-center font-18">{{ isset($url) ? ucwords($url) : ""}} Sign In</h4>

                            <div class="p-2">
                                @isset($url)
                                <form class="form-horizontal m-t-20" name="login_form" method="POST" action='{{ url("$url/login") }}' aria-label="{{ __('Login') }}">
                                @else
                                <form  class="form-horizontal m-t-20" name="login_form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                                @endisset
                                    @csrf
                                    <input type="hidden" name="timezone" id="get_timezone">
                                <div class="form-group row">
                                        <div class="col-12">
                                            <input required parsley-type="email" value="{{old('email')}}"   class="form-control @if (session('error')) is-invalid @endif @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="Email" autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @if (session('error'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ session('error') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input  required data-parsley-minlength="6" class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" autocomplete="password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old("remember") ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-10 mb-0 row">
                                        <!-- <div class="col-sm-7 m-t-20">
                                            <a href="" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div> -->
                                        <!-- <div class="col-sm-5 m-t-20">
                                            <a href='{{url("register")}}' class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a>
                                        </div> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
   
    $("form[name='login_form']").parsley();
          $("#get_timezone").val(Intl.DateTimeFormat().resolvedOptions().timeZone);
    // $("form[name='login_form']").validate({
    //     rules: {
    //         'email': { required: true, email:true },
    //         'password': { required: true },
    //     },
    //     messages: {
    //         'email': {required:"Please enter Email ID", email:"Please enter valid Email ID"},
    //         'password': "Please enter Password",
    //     },
    //     submitHandler: function (form) {
    //         form.submit();
    //     }
    // });
});
</script>
@endsection