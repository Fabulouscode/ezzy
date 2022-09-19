@foreach ($doctors as $item)
<div class="col-lg-12">
    <div class="why-choose-text wow fadeInUp mb-4" data-wow-duration="2s">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        {{-- <img src="https://images.pexels.com/photos/2182976/pexels-photo-2182976.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="img-fluid rounded" alt=""> --}}
                        <img src="{{$item->profile_image}}" class="img-fluid rounded" alt="">
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