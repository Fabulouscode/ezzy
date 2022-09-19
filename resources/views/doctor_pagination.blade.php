@if($doctors->isNotEmpty())
@foreach ($doctors as $item)
<div class="col-lg-12">
    <div class="why-choose-text wow fadeInUp mb-4" data-wow-duration="2s">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="doctor_page_card_img_container">
                            <img src="{{$item->profile_image}}" class="img-fluid rounded" alt="">
                            {{-- <img src="https://ezzycare.com/storage/images/profile_picture/1637253543_ezzy_care_2021-11-1822:09:02.921192.jpg" class="img-fluid rounded" alt=""> --}}
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h3 class="mt-0">{{$item->first_name}} {{$item->last_name}}</h3>
                        <span>{{@$item->userDetails->about_us}}</span><br/>
                        @if (@$item->userEduction->isNotEmpty())
                            <span>
                                @foreach ($item->userEduction as $education)
                                    {{$education->degree_name.' '}}
                                @endforeach
                            </span><br/>
                        @endif
                        <span>City : {{$item->userDetails->city}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="col-lg-12 js_pagination">
    {!! $doctors->links() !!}
</div>
@else
<div class="col-lg-12" style="margin-top: 300px">
    <div class="why-choose-text wow fadeInUp mb-4" data-wow-duration="2s">
        <div class="card">
            <div class="card-body">
                <div class="row" >                                        
                    <div class="col-lg-12" >
                        <h5 class="mt-0">No data found</h5>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif