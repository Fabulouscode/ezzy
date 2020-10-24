
$(function () {
    $("form[name='medicine_details_form']").parsley();
    $('#medicine_details_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            url: medicine_details_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'medicine_name', name: 'medicine_name' },
            { data: 'medicine_sku', name: 'medicine_sku' },
            { data: 'medicine_subcategory', name: 'medicineSubcategory.name' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });
    if ($('#medicine_category_id').val()) {
        changeCategory($('#medicine_category_id').val());
    }
    $('#medicine_category_id').on('change', function () {
        changeCategory($('#medicine_category_id').val());
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
                    url: medicine_details_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, 'EzzyCare App');
                        var oTable = $('#medicine_details_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error(error.msg, 'EzzyCare App');
                    }
                });
            }
        });
    }
}

function changeCategory(cat_id) {
    $("#medicine_subcategoy_id").val('');
    $("#medicine_subcategoy_id  option").each(function () {
        if ($(this).attr('data-cat_id') == cat_id) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    if (medicine_subcategoy_id != '') {
        $("#medicine_subcategoy_id").val(medicine_subcategoy_id);
    }
}