
$(function () {
    $("form[name='user_form']").parsley();

    var segment_str = window.location.pathname;
    var segment_array = segment_str.split('/');
    var last_segment = segment_array.pop();
    $('#user_review_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_review_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: { 'user_id': last_segment }
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'userDetails_email', name: 'userDetails_email' },
            { data: 'userDetails_mobile', name: 'userDetails_mobile' },
            {
                data: 'rating', name: 'rating',
                render: function (data, type, row) {
                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted" data-readonly value = "' + data + '" />';
                }
            },
            { data: 'comment', name: 'comment' },
            { data: 'status', name: 'status' },
            { data: 'review_date', name: 'review_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']]
    });


    $('input.check').on('change', function () {
        alert('Rating: ' + $(this).val());
    });
    $('.rating-tooltip').rating({
        extendSymbol: function (rate) {
            $(this).tooltip({
                container: 'body',
                placement: 'bottom',
                title: 'Rate ' + rate
            });
        }
    });
    $('.rating-tooltip-manual').rating({
        extendSymbol: function () {
            var title;
            $(this).tooltip({
                container: 'body',
                placement: 'bottom',
                trigger: 'manual',
                title: function () {
                    return title;
                }
            });
            $(this).on('rating.rateenter', function (e, rate) {
                title = rate;
                $(this).tooltip('show');
            })
                .on('rating.rateleave', function () {
                    $(this).tooltip('hide');
                });
        }
    });
    $('.rating').each(function () {
        $('<span class="badge badge-info"></span>')
            .text($(this).val() || ' ')
            .insertAfter(this);
    });
    $('.rating').on('change', function () {
        $(this).next('.badge').text($(this).val());
    });

});



function deleteRow(row_id) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_review_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    }
}
