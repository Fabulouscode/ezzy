@extends('layouts.auth')

@section('title','Lock Screen')

@section('content')
<div class="content-desc-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card">
                    <div class="card-body">
    
                        <h3 class="text-center mt-0 m-b-15">
                            <a href="{{ url('/dashboard') }}" class="logo logo-admin"><img src="{{ asset('admin/images/logo-dark.png')}}" height="80" alt="logo"></a>
                        </h3>

                        <div class="p-3">
                             <form class="form-horizontal m-t-20" name="lockscreen_form" method="POST" action="{{ url('/admin/lockscreen') }}" >
                                    @csrf
                                <div class="user-thumb text-center m-b-30">
                                    <img src="{{ asset('admin/images/avatar.jpg')}}" class="rounded-circle img-thumbnail mx-auto d-block" alt="thumbnail">
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input name="password" class="form-control @if (session('error')) is-invalid @endif" type="password" required placeholder="Password">
                                        @if (session('error'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ session('error') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="form-group text-center row m-t-20">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
    
                                <div class="form-group m-t-10 mb-0 row">
                                    <div class="col-12 m-t-20 text-center">
                                        <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-muted">Not you?</a>
                                    </div>
                                </div>
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
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
    $("form[name='lockscreen_form']").parsley();        
});
</script>
@endsection