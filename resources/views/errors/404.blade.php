@extends('errors::minimal')

@section('title','Page Not Found')

@section('content')
<div class="accountbg">
    <div class="content-center">
        <div class="content-desc-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8">
                        <div class="card">
                            <div class="card-block">
                                <div class="ex-page-content text-center">
                                    <h1 class="text-primary">404!</h1>
                                    <h3 class="">Sorry, page not found</h3><br>
                                    @if(strpos($_SERVER['REQUEST_URI'], "donotezzycaretouch") == true)
                                        <a class="btn btn-primary mb-5 waves-effect waves-light" href="{{url('/donotezzycaretouch')}}">Back to Dashboard</a>
                                    @else
                                        <a class="btn btn-primary mb-5 waves-effect waves-light" href="{{url('/')}}">Back to Homepage</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection