@extends('frontlayout.master')

@section('content')
<section id="aboutus" class="why-choose pt-100 pb-100">
    <div class="container">
        <div class="row align-items-right">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="form-group">
                    <select name="search" id="search" class="form-control">
                        <option value="" >Select Category</option>
                        @foreach ($category as $key => $item)
                            <option value={{$key}} >{{$item}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>                  
        <div class="row align-items-center" id="js_doctor_list">                    
            @include('doctor_pagination')
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">

$('#js_doctor_list').on('click','a',function(e){
    e.preventDefault();
    let page_url = $(this).attr('href');
    let search = $("#search").val();
    $.ajax({
        type:"GET",
        url: page_url,
        data: {'search':search},
        dataType: 'html',
        success: function(response){
            $("#js_doctor_list").html(response)
        }
    });
});

$('#search').change(function(e){
    e.preventDefault();
    let page_url = "{{route('doctors')}}";
    let search = $(this).val();
    if (search != '') {
        $.ajax({
            type:"GET",
            url: page_url,
            data: {'search':search},
            dataType: 'html',
            success: function(response){
                $("#js_doctor_list").html(response)
            }
        });        
    }
});

</script>
@endsection
