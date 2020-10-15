
$(function () {
    $("form[name='appointments_form']").parsley();
    $('#appointments_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            url: appointment_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'appointment_time', name: 'appointment_time' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
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
            console.log(row_id);
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
                        toastr.success(data.msg, 'EazzyCare App');
                        var oTable = $('#appointments_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error(error.msg, 'EazzyCare App');
                    }
                });
            }
        });
    }
}    