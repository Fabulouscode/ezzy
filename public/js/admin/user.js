
$(function () {
    $("form[name='user_form']").parsley();
    $('#user_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'email', name: 'email' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'categoryParent', name: 'categoryParent' },
            { data: 'categoryChild', name: 'categoryChild' },
            { data: 'status', name: 'status' },
            // { data: 'actiondetails', name: 'actiondetails', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            if (data_obj.category_id == '') {
                api.columns([5, 6]).visible(showColumn);
            } else if (data_obj.category_id == '2' || data_obj.category_id == '3') {
                api.columns([6]).visible(showColumn);
            }
        }
    });

    // var segment_str = window.location.pathname;
    // var segment_array = segment_str.split('/');
    // var last_segment = segment_array.pop();
    // console.log(last_segment);
    // $('#user_review_datatable').DataTable({
    //     lengthChange: true,
    //     processing: true,
    //     serverSide: true,
    //     bPaginate: true,
    //     responsive: true,
    //     ajax: {
    //         headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
    //         url: user_url + "/review/" + last_segment,
    //         type: 'get',
    //         dataType: "json",
    //         async: true,
    //     },
    //     columns: [
    //         { data: 'first_name', name: 'first_name' },
    //         { data: 'last_name', name: 'last_name' },
    //         { data: 'email', name: 'email' },
    //         { data: 'mobile_no', name: 'mobile_no' },
    //         { data: 'categoryParent', name: 'categoryParent.name' },
    //         { data: 'categoryChild', name: 'categoryChild.name' },
    //         { data: 'status', name: 'status' },
    //         { data: 'action', name: 'action', orderable: false, searchable: false },
    //     ],
    //     order: [[0, 'desc']]
    // });

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
                    url: user_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    }
}

function changeStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    }
}    