
$(document).ready(function () {
    // $("form[name='category_form']").validate({
    //     rules: {
    //         'name': { required: true },
    //         'parent_id': { required: true },
    //     },
    //     messages: {
    //         'name': "Please enter Category Name",
    //         'parent_id': "Please select Parent Category",
    //     },
    //     submitHandler: function (form) {
    //         form.submit();
    //     }
    // });
    $("form[name='category_form']").parsley();
    // toastr.success('We do have the Kapua suite available.', 'Success Alert', { timeOut: 5000 })
    $('#category_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            url: category_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'categoryParent', name: 'categoryParent' },
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
                    url: category_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        // toastr.success(data.msg, 'EazzyCare App');
                        var oTable = $('#category_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                        // toastr.error(error.msg, 'EazzyCare App');
                    }
                });
            }
        });
    }
}    