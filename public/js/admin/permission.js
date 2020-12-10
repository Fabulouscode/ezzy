
$(function () {
    $("form[name='permission_form']").parsley();
    $('#permission_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: permission_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'permission_category', name: 'permission_category' },
            { data: 'permission_title', name: 'permission_title' },
            { data: 'permission_name', name: 'permission_name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });

    $(document).on('submit', '#permission_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: permission_url,
            data: $('#permission_form').serialize(),
            success: function (response) {
                $('#permission_id').val('');
                $('#permission_category_id').val('');
                $('#permission_title').val('');
                $('#permission_name').val('');
                $('#addPermission').modal('hide');
                var oTable = $('#permission_datatable').dataTable();
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
        url: permission_url + '/create',
        success: function (response) {
            if (response.status) {
                $("form[name='permission_form']").parsley().destroy();
                $("form[name='permission_form']").parsley();
                $('.modal-title').text('Add Permission Details');
                $('#submit_btn').text('Add');
                $('#permission_id').val('');
                $('#permission_category_id').val('');
                $('#permission_title').val('');
                $('#permission_name').val('');
                if (response.permission_cats) {
                    $("#permission_category_id option").remove();
                    response.permission_cats.forEach(element => {
                        $('#permission_category_id').append(new Option(element.name, element.id));
                    });
                }
                setTimeout(function () {
                    $('#permission_title').focus();
                }, 1000);
                $('#addPermission').modal();
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
        url: permission_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='permission_form']").parsley().destroy();
                $("form[name='permission_form']").parsley();
                $('.modal-title').text('Edit Permission Details');
                $('#submit_btn').text('Update');
                if (response.permission_cats) {
                    $("#permission_category_id option").remove();
                    response.permission_cats.forEach(element => {
                        $('#permission_category_id').append(new Option(element.name, element.id));
                    });
                }
                if (response.data) {
                    $('#permission_id').val(response.data.id);
                    $('#permission_category_id').val(response.data.permission_category_id);
                    $('#permission_title').val(response.data.permission_title);
                    $('#permission_name').val(response.data.permission_name);
                }
                setTimeout(function () {
                    $('#permission_title').focus();
                }, 1000);
                $('#addPermission').modal();
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
                    url: permission_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#permission_datatable').dataTable();
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

