@extends('layouts.backend')

@section('title', 'App Setting')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ url('/donotezzycaretouch/healthcare/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">App Setting</li>
                    </ol>
                </div>
                <h5 class="page-title">App Setting</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">

                        <form method="POST" action="{{ url('donotezzycaretouch/app_setting') }}" id="user_form"
                            name="user_form" enctype="multipart/form-data">
                            @csrf
                            <input id="id" type="hidden" name="id"
                                value="{{ !empty($data->id) ? $data->id : '' }}">
                            <div class="border border-light rounded mb-3">
                                <div class="card-detail-view">
                                    <h4 class="mt-0 mb-0 header-title">App Setting</h4>
                                    <div class="card-detail-list">
                                        <div class="row">
                                            <dt class="col-sm-5"><label>PayStack</label></dt>
                                            <dd class="col-sm-7">
                                                <select class="form-control" id="setting[paystack]" name="setting[paystack]" >
                                                    <option value="1" {{isset($data['paystack']) && $data['paystack'] == 1 ? 'selected' :""}}>Start</option>
                                                    <option value="0" {{isset($data['paystack']) && $data['paystack'] == 0 ? 'selected' :""}}>Stop</option>
                                                </select>
                                                @error('setting[paystack]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </dd>
                                        </div>
                                        <div class="row">
                                            <dt class="col-sm-5"><label>PayStack QR Code</label></dt>
                                            <dd class="col-sm-7">
                                                <select class="form-control" id="setting[paystack_qr_code]" name="setting[paystack_qr_code]" >
                                                    <option value="1" {{isset($data['paystack_qr_code']) && $data['paystack_qr_code'] == 1 ? 'selected' :""}}>Start</option>
                                                    <option value="0" {{isset($data['paystack_qr_code']) && $data['paystack_qr_code'] == 0 ? 'selected' :""}}>Stop</option>
                                                </select>
                                                @error('setting[paystack_qr_code]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </dd>
                                        </div>
                                        <div class="row">
                                             <dt class="col-sm-5"><label>PayStack Card</label></dt>
                                             <dd class="col-sm-7">
                                                 <select class="form-control" id="setting[paystack_card]" name="setting[paystack_card]" >
                                                     <option value="1" {{isset($data['paystack_card']) && $data['paystack_card'] == 1 ? 'selected' :""}}>Start</option>
                                                     <option value="0" {{isset($data['paystack_card']) && $data['paystack_card'] == 0 ? 'selected' :""}}>Stop</option>
                                                 </select>
                                                 @error('setting[paystack_card]')
                                                     <span class="invalid-feedback" role="alert">
                                                         <strong>{{ $message }}</strong>
                                                     </span>
                                                 @enderror
                                             </dd>
                                         </div>
                                         <div class="row">
                                             <dt class="col-sm-5"><label>PayStack Bank</label></dt>
                                             <dd class="col-sm-7">
                                                 <select class="form-control" id="setting[paystack_bank]" name="setting[paystack_bank]" >
                                                     <option value="1" {{isset($data['paystack_bank']) && $data['paystack_bank'] == 1 ? 'selected' :""}}>Start</option>
                                                     <option value="0" {{isset($data['paystack_bank']) && $data['paystack_bank'] == 0 ? 'selected' :""}}>Stop</option>
                                                 </select>
                                                 @error('setting[paystack_bank]')
                                                     <span class="invalid-feedback" role="alert">
                                                         <strong>{{ $message }}</strong>
                                                     </span>
                                                 @enderror
                                             </dd>
                                         </div>
                                         <div class="row">
                                             <dt class="col-sm-5"><label>PayStack USSD</label></dt>
                                             <dd class="col-sm-7">
                                                 <select class="form-control" id="setting[paystack_ussd]" name="setting[paystack_ussd]" >
                                                     <option value="1" {{isset($data['paystack_ussd']) && $data['paystack_ussd'] == 1 ? 'selected' :""}}>Start</option>
                                                     <option value="0" {{isset($data['paystack_ussd']) && $data['paystack_ussd'] == 0 ? 'selected' :""}}>Stop</option>
                                                 </select>
                                                 @error('setting[paystack_ussd]')
                                                     <span class="invalid-feedback" role="alert">
                                                         <strong>{{ $message }}</strong>
                                                     </span>
                                                 @enderror
                                             </dd>
                                         </div>
                                        
                                        <div class="row mt-5">
                                            <dt class="col-sm-5"><label>InterSwitch</label></dt>
                                            <dd class="col-sm-7">
                                                <select class="form-control" id="setting[interswitch]" name="setting[interswitch]" >
                                                    <option value="1" {{isset($data['interswitch']) &&  $data['interswitch'] == 1 ? 'selected' :""}}>Start</option>
                                                    <option value="0" {{isset($data['interswitch']) && $data['interswitch'] == 0 ? 'selected' :""}}>Stop</option>
                                                </select>
                                                @error('setting[interswitch]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </dd>
                                        </div>
                                       
                                        <div class="row mt-5">
                                            <dt class="col-sm-5"><label>Registration (Start/Stop)</label></dt>
                                            <dd class="col-sm-7">
                                                <select class="form-control" id="setting[registration_start]" name="setting[registration_start]" >
                                                    <option value="1" {{isset($data['registration_start']) &&  $data['registration_start'] == 1 ? 'selected' :""}}>Start</option>
                                                    <option value="0" {{isset($data['registration_start']) && $data['registration_start'] == 0 ? 'selected' :""}}>Stop</option>
                                                </select>
                                                @error('setting[registration_start]')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </dd>
                                        </div>
                                        <div class="row d-flex justify-content-end mt-5">
                                            <div class="form-group col-md-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-info waves-effect m-l-5">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
