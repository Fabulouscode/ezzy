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
                                    <h1 class="text-primary">4<i class="fa fa-smile-o text-warning ml-1 mr-1"></i>4!</h1>
                                    <h3 class="">Sorry, page not found</h3><br>
            
                                    <a class="btn btn-primary mb-5 waves-effect waves-light" href="{{url('')}}">Back to Dashboard</a>
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