
$(function () {
    $("form[name='medicine_category_form']").parsley();
    $('#medicine_category_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: medicine_category_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
    });

    $(document).on('submit', '#medicine_category_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: medicine_category_url,
            data: $('#medicine_category_form').serialize(),
            success: function (response) {
                $('#medicine_category_id').val('');
                $('#medicine_category_name').val('');
                $('#medicine_category_status').val('');
                $('#addMedicineCategory').modal('hide');
                var oTable = $('#medicine_category_datatable').dataTable();
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
        url: medicine_category_url + '/create',
        success: function (response) {
            if (response.status) {
                $("form[name='medicine_category_form']").parsley().destroy();
                $("form[name='medicine_category_form']").parsley();
                $('.modal-title').text('Add Medicine Category Details');
                $('#submit_btn').text('Add');
                $('#medicine_category_id').val('');
                $('#medicine_category_name').val('');
                $('#medicine_category_status').val('');
                if (response.medicine_status) {
                    $("#medicine_category_status option").remove();
                    response.medicine_status.forEach((element, key) => {
                        $('#medicine_category_status').append(new Option(element, key));
                    });
                }
                setTimeout(function () {
                    $('#medicine_category_name').focus();
                }, 1000);
                $('#addMedicineCategory').modal();
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
        url: medicine_category_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='medicine_category_form']").parsley().destroy();
                $("form[name='medicine_category_form']").parsley();
                $('.modal-title').text('Edit Medicine Category Details');
                $('#submit_btn').text('Update');
                if (response.medicine_status) {
                    $("#medicine_category_status option").remove();
                    response.medicine_status.forEach((element, key) => {
                        $('#medicine_category_status').append(new Option(element, key));
                    });
                }
                if (response.data) {
                    $('#medicine_category_id').val(response.data.id);
                    $('#medicine_category_name').val(response.data.name);
                    $('#medicine_category_status').val(response.data.status);
                }
                setTimeout(function () {
                    $('#medicine_category_name').focus();
                }, 1000);
                $('#addMedicineCategory').modal();
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
                    url: medicine_category_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, 'EzzyCare App');
                        var oTable = $('#medicine_category_datatable').dataTable();
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