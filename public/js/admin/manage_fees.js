
$(function () {
    $("form[name='manage_fees_form']").parsley();
    $('#manage_fees_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: manage_fees_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'category', name: 'category' },
            { data: 'fees_percentage', name: 'fees_percentage' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });

    $(document).on('submit', '#manage_fees_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: manage_fees_url,
            data: $('#manage_fees_form').serialize(),
            success: function (response) {
                $('#fees_id').val('');
                $('#category_id').val('');
                $('#fees_percentage').val('');
                $('#addManageFees').modal('hide');
                var oTable = $('#manage_fees_datatable').dataTable();
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
        url: manage_fees_url + '/create',
        success: function (response) {
            if (response.status) {
                console.log(response);
                $('.modal-title').text('Add Manage Fees Details');
                $('#submit_btn').text('Add');
                $('#fees_id').val('');
                $('#category_id').val('');
                $('#fees_percentage').val('');
                if (response.hcp_types) {
                    $("#category_id option").remove();
                    response.hcp_types.forEach(element => {
                        $('#category_id').append(new Option(element.name, element.id));
                    });
                }
                $('#addManageFees').modal();
            }
            else {
                toastr.error(error.responseJSON.msg, 'EzzyCare App');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        },
    });
}

function editRow(id) {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: manage_fees_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $('.modal-title').text('Edit Manage Fees Details');
                $('#submit_btn').text('Update');
                if (response.hcp_types) {
                    $("#category_id option").remove();
                    response.hcp_types.forEach(element => {
                        $('#category_id').append(new Option(element.name, element.id));
                    });
                }
                if (response.data) {
                    $('#fees_id').val(response.data.id);
                    $('#category_id').val(response.data.category_id);
                    $('#fees_percentage').val(response.data.fees_percentage);
                }
                $('#addManageFees').modal();
            }
            else {
                toastr.error(error.responseJSON.msg, 'EzzyCare App');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
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
                    url: manage_fees_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#manage_fees_datatable').dataTable();
                        oTable.fnDraw(false);
                        toastr.success(data.msg, 'EzzyCare App');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error(error.responseJSON.msg, 'EzzyCare App');
                    }
                });
            }
        });
    }
}

