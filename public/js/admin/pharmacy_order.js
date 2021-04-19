
$(function () {
    $("form[name='pharmacy_order_form']").parsley();
    $('#pharmacy_order_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: pharmacy_order_url,
            type: 'get',
            dataType: "json",
            async: true
        },
        columns: [
            { data: 'id', name: 'orders.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

    $('#order_review_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: pharmacy_order_url + '/reviews',
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'orders.id', searchable: false },
            { data: 'order_no', name: 'order_no' , searchable: false},
            { data: 'user_name', name: 'user_name' },
            { data: 'patient_name', name: 'patient_name' },
            {
                data: 'user_rating', name: 'orders.user_rating',
                render: function (data, type, row) {
                    if (data == '' || data == null) {
                        data = '0';
                    }
                    data = parseFloat(data).toFixed(2);
                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted" data-readonly value = "' + data + '" />';
                }
            },
            { data: 'user_review', name: 'orders.user_review' },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
            var ratingInput = $(row).find('.rating');
            $(ratingInput).rating();
        },
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        },
        drawCallback: function (settings) {
            $('.rating').each(function () {
                $('<span class="badge badge-info"></span>')
                    .text($(this).val() || ' ')
                    .insertAfter(this);
            });
        }
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
                    url: pharmacy_order_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#pharmacy_order_datatable').dataTable();
                        oTable.fnDraw(false);
                        toastr.success(data.msg, App_name_global);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

