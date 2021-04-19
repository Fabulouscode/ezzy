
$(function () {
    $("form[name='medicine_subcategory_form']").parsley();
    $('#medicine_subcategory_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: medicine_subcategory_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'medicine_subcategories.id', searchable: false },
            { data: 'name', name: 'name' },
            { data: 'medicineCategory', name: 'medicineCategory' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

    $(document).on('submit', '#medicine_subcategory_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: medicine_subcategory_url,
            data: $('#medicine_subcategory_form').serialize(),
            success: function (response) {
                $('#medicine_subcategory_id').val('');
                $('#medicine_subcategory_name').val('');
                $('#medicine_category').val('');
                $('#medicine_subcategory_status').val('');
                $('#addMedicineSubcategory').modal('hide');
                var oTable = $('#medicine_subcategory_datatable').dataTable();
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
        url: medicine_subcategory_url + '/create',
        success: function (response) {
            if (response.status) {
                $("form[name='medicine_subcategory_form']").parsley().destroy();
                $("form[name='medicine_subcategory_form']").parsley();
                $('.modal-title').text('Add Medicine Subcategory Details');
                $('#submit_btn').text('Add');
                $('#medicine_subcategory_id').val('');
                $('#medicine_subcategory_name').val('');
                $('#medicine_category').val('');
                $('#medicine_subcategory_status').val('');
                if (response.medicine_status) {
                    $("#medicine_subcategory_status option").remove();
                    response.medicine_status.forEach((element, key) => {
                        $('#medicine_subcategory_status').append(new Option(element, key));
                    });
                }
                if (response.medicine_category) {
                    $("#medicine_category option").remove();
                    response.medicine_category.forEach((element, key) => {
                        $('#medicine_category').append(new Option(element.name, element.id));
                    });
                }
                setTimeout(function () {
                    $('#medicine_subcategory_name').focus();
                }, 1000);
                $('#addMedicineSubcategory').modal();
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
        url: medicine_subcategory_url + '/' + id + '/edit',
        success: function (response) {
            if (response.status) {
                $("form[name='medicine_subcategory_form']").parsley().destroy();
                $("form[name='medicine_subcategory_form']").parsley();
                $('.modal-title').text('Edit Medicine Subcategory Details');
                $('#submit_btn').text('Update');
                if (response.medicine_status) {
                    $("#medicine_subcategory_status option").remove();
                    response.medicine_status.forEach((element, key) => {
                        $('#medicine_subcategory_status').append(new Option(element, key));
                    });
                }
                if (response.medicine_category) {
                    $("#medicine_category option").remove();
                    response.medicine_category.forEach((element, key) => {
                        $('#medicine_category').append(new Option(element.name, element.id));
                    });
                }
                if (response.data) {
                    $('#medicine_subcategory_id').val(response.data.id);
                    $('#medicine_subcategory_name').val(response.data.name);
                    $('#medicine_category').val(response.data.medicine_category_id);
                    $('#medicine_subcategory_status').val(response.data.status);
                }
                setTimeout(function () {
                    $('#medicine_subcategory_name').focus();
                }, 1000);
                $('#addMedicineSubcategory').modal();
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
                    url: medicine_subcategory_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#medicine_subcategory_datatable').dataTable();
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


