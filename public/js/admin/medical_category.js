
$(function () {
    $("form[name='medical_category_form']").parsley();
    $('#medical_category_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: medical_category_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'medical_category_name', name: 'medical_category_name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

    $(document).on('submit', '#medical_category_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: medical_category_url,
            data: $('#medical_category_form').serialize(),
            success: function (response) {
                $('#medical_category_id').val('');
                $('#medical_category_name').val('');
                $('#medical_category_status').val('');
                $('#addMedicalCategory').modal('hide');
                var oTable = $('#medical_category_datatable').dataTable();
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
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: medical_category_url + '/create',
        success: function (response) {
            if (response.status) {
                $("form[name='medical_category_form']").parsley().destroy();
                $("form[name='medical_category_form']").parsley();
                $('.modal-title').text('Add Medical Category Details');
                $('#submit_btn').text('Add');
                $('#medical_category_id').val('');
                $('#medical_category_name').val('');
                $('#medical_category_status').val('');
                if (response.medical_status) {
                    $("#medical_category_status option").remove();
                    response.medical_status.forEach((element, key) => {
                        $('#medical_category_status').append(new Option(element, key));
                    });
                }
                setTimeout(function () {
                    $('#medical_category_name').focus();
                }, 1000);
                $('#addMedicalCategory').modal();
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

function editRow(id) {
    $.ajax({
        type: 'get',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: medical_category_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='medical_category_form']").parsley().destroy();
                $("form[name='medical_category_form']").parsley();
                $('.modal-title').text('Edit Medical Category Details');
                $('#submit_btn').text('Update');
                if (response.medicine_status) {
                    $("#medicine_category_status option").remove();
                    response.medicine_status.forEach((element, key) => {
                        $('#medical_category_status').append(new Option(element, key));
                    });
                }
                if (response.data) {
                    $('#medical_category_id').val(response.data.id);
                    $('#medical_category_name').val(response.data.medical_category_name);
                    $('#medical_category_status').val(response.data.status);
                }
                setTimeout(function () {
                    $('#medical_category_name').focus();
                }, 1000);
                $('#addMedicalCategory').modal();
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
                    url: medical_category_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#medical_category_datatable').dataTable();
                        oTable.fnDraw(true);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}