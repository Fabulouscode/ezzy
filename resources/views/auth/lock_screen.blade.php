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
                            <a href="{{ url('/') }}" class="logo logo-admin"><img src="{{ asset('admin/images/new_logo-dark.png')}}" height="30" alt="logo"></a>
                        </h3>
    
                        <div class="p-3">
                             <form class="form-horizontal m-t-20" name="lockscreen_form" method="POST" action='{{ url("admin/lockscreen") }}' >
    
                                <div class="user-thumb text-center m-b-30">
                                    <img src="{{ asset('admin/images/users/user-4.jpg')}}" class="rounded-circle img-thumbnail mx-auto d-block" alt="thumbnail">
                                </div>
    
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="password" required="" placeholder="Password">
                                    </div>
                                </div>
    
                                <div class="form-group text-center row m-t-20">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
    
                                <div class="form-group m-t-10 mb-0 row">
                                    <div class="col-12 m-t-20 text-center">
                                        <a href="{{ url('/login') }}" class="text-muted">Not you?</a>
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

@endsection

@section('script')
<script>
$(document).ready(function () {
    $("form[name='lockscreen_form']").parsley();        
});
</script>
@endsection