
$(function () {
    $("form[name='permission_category_form']").parsley();
    $('#permission_category_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: permission_category_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });

    $(document).on('submit', '#permission_category_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: permission_category_url,
            data: $('#permission_category_form').serialize(),
            success: function (response) {
                $('#permission_category_id').val('');
                $('#name').val('');
                $('#addPermissionCategory').modal('hide');
                var oTable = $('#permission_category_datatable').dataTable();
                oTable.fnDraw(false);
                toastr.success(response.msg, 'EzzyCare App');
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                $.each(myArr.errors, function (index, value) {
                    toastr.error(value, 'Vyzum App');
                });
                return false;
            },
        });
        return false;
    });
});


function addRow() {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: permission_category_url + '/create',
        success: function (response) {
            if (response.status) {
                $('.modal-title').text('Add Permission Category Details');
                $('#submit_btn').text('Add');
                $('#addPermissionCategory').modal();
            }
            else {
                toastr.error(error.responseJSON.msg, 'EzzyCare App');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.msg, 'EzzyCare App');
            return false;
        },
    });

}

function editRow(id) {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: permission_category_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $('.modal-title').text('Edit Permission Category Details');
                $('#submit_btn').text('Update');
                if (response.data) {
                    $('#permission_category_id').val(response.data.id);
                    $('#name').val(response.data.name);
                }
                $('#addPermissionCategory').modal();
            }
            else {
                toastr.error(error.responseJSON.msg, 'EzzyCare App');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.msg, 'EzzyCare App');
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
                    url: permission_category_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#permission_category_datatable').dataTable();
                        oTable.fnDraw(false);
                        toastr.success(data.msg, 'EzzyCare App');
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, 'EzzyCare App');
                    }
                });
            }
        });
    }
}

