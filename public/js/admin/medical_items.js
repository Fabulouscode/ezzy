
$(function () {
    $("form[name='medical_item_form']").parsley();
    $('#medical_item_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: medical_item_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'medical_item_name', name: 'Medical Name' },
            { data: 'medical_category', name: 'Medical Category' },
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

    $(document).on('submit', '#medical_item_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: medical_item_url,
            data: $('#medical_item_form').serialize(),
            success: function (response) {
                $('#medical_item_id').val('');
                $('#medical_item_name').val('');
                $('#medical_category').val('');
                $('#medical_item_status').val('');
                $('#addMedicalItem').modal('hide');
                var oTable = $('#medical_item_datatable').dataTable();
                oTable.fnDraw(false);
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
        url: medical_item_url + '/create',
        success: function (response) {
            if (response.status) {
                $("form[name='medical_item_form']").parsley().destroy();
                $("form[name='medical_item_form']").parsley();
                $('.modal-title').text('Add Medical Item Details');
                $('#submit_btn').text('Add');
                $('#medical_item_id').val('');
                $('#medical_item_name').val('');
                $('#medical_category').val('');
                $('#medical_item_status').val('');
                if (response.medical_status) {
                    $("#medical_item_status option").remove();
                    response.medical_status.forEach((element, key) => {
                        $('#medical_item_status').append(new Option(element, key));
                    });
                }
                if (response.medical_category) {
                    $("#medical_category option").remove();
                    response.medical_category.forEach((element, key) => {
                        $('#medical_category').append(new Option(element.medical_category_name, element.id));
                    });
                }
                setTimeout(function () {
                    $('#medical_item_name').focus();
                }, 1000);
                $('#addMedicalItem').modal();
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
        url: medical_item_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='medical_item_form']").parsley().destroy();
                $("form[name='medical_item_form']").parsley();
                $('.modal-title').text('Edit Medicine Subcategory Details');
                $('#submit_btn').text('Update');
                if (response.medical_status) {
                    $("#medical_item_status option").remove();
                    response.medical_status.forEach((element, key) => {
                        $('#medical_item_status').append(new Option(element, key));
                    });
                }
                if (response.medical_category) {
                    $("#medical_category option").remove();
                    response.medical_category.forEach((element, key) => {
                        $('#medical_category').append(new Option(element.medical_category_name, element.id));
                    });
                }
                if (response.data) {
                    $('#medical_item_id').val(response.data.id);
                    $('#medical_item_name').val(response.data.medical_item_name);
                    $('#medical_category').val(response.data.medical_category_id);
                    $('#medical_item_status').val(response.data.status);
                }
                setTimeout(function () {
                    $('#medical_item_name').focus();
                }, 1000);
                $('#addMedicalItem').modal();
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
                    url: medical_item_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#medical_item_datatable').dataTable();
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


