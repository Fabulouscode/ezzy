@extends('layouts.auth')

@section('content')
<div class="content-center">
    <div class="content-desc-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card">
                        <div class="card-body">
        
                            <h3 class="text-center mt-0 m-b-15">
                                <a href="index.html" class="logo logo-admin"><img src="{{ asset('admin/images/new_logo-dark.png')}}" height="30" alt="logo"></a>
                            </h3>
        
                            <h4 class="text-muted text-center font-18"><b>{{ isset($url) ? ucwords($url) : ""}} Register</b></h4>
        
                            <div class="p-3">
                                @isset($url)
                                <form method="POST" name='register_form' action='{{ url("$url/register") }}' aria-label="{{ __('Register') }}">
                                @else
                                <form method="POST" name='register_form' action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                                @endisset
                                    @csrf
        
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="email" type="email" placeholder="Email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" >
                                                <label class="custom-control-label font-weight-normal" for="customCheck1">I accept <a href="#" class="text-primary">Terms and Conditions</a></label>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="form-group text-center row m-t-20">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Register</button>
                                        </div>
                                    </div>
        
                                    <div class="form-group m-t-10 mb-0 row">
                                        <div class="col-12 m-t-20 text-center">
                                            <a href='{{ url("login") }}' class="text-muted">Already have account?</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
        
                        </div>
                    </div>                        
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
    $("form[name='register_form']").validate({
        rules: {
            'name': { required: true},
            'email': { required: true, email:true },
            'password': { required: true },
            'password_confirmation': { required: true },
        },
        messages: {
            'name': "Please enter Name",
            'email': {required:"Please enter Email ID", email:"Please enter valid Email ID"},
            'password': "Please enter Password",
            'password_confirmation': "Please enter Confirm Password",
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
</script>
@endsection