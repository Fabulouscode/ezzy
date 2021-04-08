
$(function () {
    $("form[name='support_request_form']").parsley();
    $('#support_request_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: support_request_url,
            type: 'get',
            dataType: "json",
            async: true
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'support_id', name: 'support_id', searchable: false },
            { data: 'userDetails', name: 'User Details' },
            { data: 'title', name: 'Subject' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });
    // $('#reply_message').keypress(function (e) {
    //     var key = e.which;
    //     if (key == 13)  // the enter key code
    //     {
    //         sendReply();
    //     }
    // });
});

function getChatMessage() {
    var support_id = $("#support_id").val();
    if (support_id != '') {
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: support_request_url + "/chat_msg/" + support_id,
            type: "get",
            success: function (data) {
                $('body').find('#chat_window').empty();
                $('body').find('#chat_window').html(data);
                if ($('.chat-scrollbar').length) {
                    $(".chat-scrollbar").mCustomScrollbar({
                        theme: "minimal-dark",
                        mouseWheelPixels: 300,
                    });
                    $(".chat-scrollbar").mCustomScrollbar("scrollTo", "bottom");
                }
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}

function sendReply() {
    var reply_msg = $('#reply_message').val();
    var support_id = $('#support_id').val();
    if (reply_msg != '' && support_id != '') {
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: support_request_url + "/chat_msg/add",
            type: "post",
            data: { 'message': reply_msg, 'support_id': support_id },
            dataType: 'json',
            success: function (data) {
                toastr.success(data.msg, App_name_global);
                $('#reply_message').val('');
                getChatMessage();
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}

function closeTicketRow(row_id) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to close this support request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: support_request_url + "/close_request/" + row_id,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status Close!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#support_request_datatable').dataTable();
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
                    url: support_request_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#support_request_datatable').dataTable();
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