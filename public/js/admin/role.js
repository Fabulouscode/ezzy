
$(function () {
    $("form[name='role_form']").parsley();
    $('#role_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: role_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'role_name', name: 'role_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
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
                    url: role_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#role_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}