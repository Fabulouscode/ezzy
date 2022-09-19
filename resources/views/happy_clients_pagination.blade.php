@foreach ($happyClients as $item)
<div class="col-lg-12">
    <div class="why-choose-text wow fadeInUp mb-4" data-wow-duration="2s">
        <div class="card">
            <div class="card-body">
                <div class="row">                                        
                    <div class="col-lg-12">
                        <h5 class="mt-0">Consulted By : {{@$item->user->first_name}} {{@$item->user->last_name}}</h5>
                        <h5 class="mt-0">Patient Name : {{$item->name}}</h5>
                        @if (!empty($item->user_review))
                            <p class="mt-0"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{$item->user_review}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="col-lg-12 js_pagination">
    {{$happyClients->links()}}
</div>