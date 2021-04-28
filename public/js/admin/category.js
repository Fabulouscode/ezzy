
$(function () {
    $("form[name='category_form']").parsley();
    $('#category_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: category_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: {
                parent_id: function () { return $('#searchByHcpType').val() }, 
            }
        },
        columns: [
            { data: 'id', name: 'categories.id', searchable: false },
            { data: 'name', name: 'name' },
            { data: 'categoryParent', name: 'categoryParent' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });


    $('#searchByHcpType').on('change', function (ev, picker) {
        var oTable = $('#category_datatable').dataTable();
        oTable.fnDraw(true);
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
                    url: category_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#category_datatable').dataTable();
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