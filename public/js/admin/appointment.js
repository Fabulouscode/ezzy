
$(function () {
    $("form[name='appointment_form']").parsley();

    $('#appointments_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: appointment_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });

    $('#appointment_review_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: appointment_url + '/reviews',
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'appointment_no', name: 'Appointment No' },
            { data: 'user_name', name: 'User name' },
            { data: 'patient_name', name: 'Patient name' },
            {
                data: 'user_rating', name: 'Ratings',
                render: function (data, type, row) {
                    if (data == '' || data == null) {
                        data = '0';
                    }
                    data = parseFloat(data).toFixed(2);
                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted" data-readonly value = "' + data + '" />';
                }
            },
            { data: 'user_review', name: 'Reviews' },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
            var ratingInput = $(row).find('.rating');
            $(ratingInput).rating();
        },
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
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
                    url: appointment_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, 'EzzyCare App');
                        var oTable = $('#appointments_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, 'EzzyCare App');
                    }
                });
            }
        });
    }
}    