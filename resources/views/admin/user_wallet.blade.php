@extends('layouts.backend')

@section('title', 'User Wallet')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch/user_wallet') }}">User Wallet</a>
                        </li>
                    </ol>
                </div>
                <h5 class="page-title">User Wallet</h5>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST" action="{{ url('donotezzycaretouch/user_wallet') }}" id="user_form"
                            name="user_form">
                            @csrf
                            <div class="border border-light rounded mb-3">
                                <div class="card-detail-view">
                                    <div class="card-detail-list">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Category</label>
                                                <select class="form-control  @error('category_id') is-invalid @enderror"
                                                    name="category_id" id="category_id">
                                                    @if (!empty($categories))
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $key => $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                        <option value="0">Patient</option>
                                                    @endif
                                                </select>
                                                @error('category_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Users</label>
                                                <select class="form-control @error('user_id') is-invalid @enderror"
                                                    name="user_id" id="user_id">
                                                    {{-- @if (!empty($users))
                                                    <option value="">Select Users</option>
                                                    @foreach ($users as $key => $user)
                                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} ({{$user->email}})</option>
                                                    @endforeach
                                                @endif --}}
                                                </select>
                                                @error('user_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-info waves-effect m-l-5">
                                        Submit
                                    </button>
                                    {{-- <a href="#" onclick="history.go(-1)">
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                            Cancel
                                        </button>
                                    </a> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        var user_wallet_url = "{{ url('/donotezzycaretouch/user_wallet/get-user') }}";
        var data_obj = {};
    </script>
    <script src="{{ asset('js/admin/user_wallet.js') }}"></script>
@endsection
