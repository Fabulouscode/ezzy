
$(function () {
    $("form[name='service_usage_form']").parsley();
    $('#service_usage_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: service_usage_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });

    $(document).on('submit', '#service_usage_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: service_usage_url,
            data: $('#service_usage_form').serialize(),
            success: function (response) {
                $('#service_usage_id').val('');
                $('#service_usage_name').val('');
                $('#addService').modal('hide');
                var oTable = $('#service_usage_datatable').dataTable();
                oTable.fnDraw(true);
                toastr.success(response.msg, App_name_global);
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                $.each(myArr.errors, function (index, value) {
                    toastr.error(value, App_name_global);
                });
                return false;
            },
        });
        return false;
    });
});


function addRow() {
    $("form[name='service_usage_form']").parsley().destroy();
    $("form[name='service_usage_form']").parsley();
    $('.modal-title').text('Add Service Details');
    $('#submit_btn').text('Add');
    $('#service_usage_id').val('');
    $('#service_usage_name').val('');
    setTimeout(function () {
        $('#service_usage_name').focus();
    }, 1000);
    $('#addService').modal();
}

function editRow(id) {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: service_usage_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='service_usage_form']").parsley().destroy();
                $("form[name='service_usage_form']").parsley();
                $('.modal-title').text('Edit Service Details');
                $('#submit_btn').text('Update');
                if (response.data) {
                    $('#service_usage_id').val(response.data.id);
                    $('#service_usage_name').val(response.data.name);
                }
                setTimeout(function () {
                    $('#service_usage_name').focus();
                }, 1000);
                $('#addService').modal();
            }
            else {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.msg, App_name_global);
            return false;
        },
    });
}

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
                    url: service_usage_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#service_usage_datatable').dataTable();
                        oTable.fnDraw(true);
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

