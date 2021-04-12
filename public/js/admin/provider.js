
$(function () {
    $("form[name='user_form']").parsley();
    var url_string = window.location.href;
    var url = new URL(url_string);
    var searchHCPtype = url.searchParams.get("hcp_type");
    $('#user_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        search: { "search": searchHCPtype },
        // responsive: true,
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
            { data: 'user_name', name: 'user_name' },
            { data: 'email', name: 'email' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'hcp_type', name: 'hcp_type' },
            {
                data: '', name: 'Ratings', orderable: false, searchable: false,
                render: function (data, type, row) {
                    var rating_count = '0';
                    if (data_obj.category_id == '2') {
                        if (row.user_order_rating != '' && row.user_order_rating != null) {
                            rating_count = row.user_order_rating;
                        }
                    } else {
                        if (row.user_appointment_rating != '' && row.user_appointment_rating != null) {
                            rating_count = row.user_appointment_rating;
                        }
                    }

                    if (rating_count == '' || rating_count == null) {
                        rating_count = '0';
                    }

                    rating_count = parseFloat(rating_count).toFixed(2);

                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-20 text-primary" data-empty="mdi mdi-star-outline font-20 text-muted" data-readonly value = "' + rating_count + '" />';
                }
            },
            { data: 'status', name: 'status' },
            // { data: 'actiondetails', name: 'actiondetails', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
            var ratingInput = $(row).find('.rating');
            $(ratingInput).rating();
        },
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
            if (data_obj.category_id == '') {
                api.columns([4]).visible(showColumn);
                api.columns([5]).visible(showColumn);
            }
            if (data_obj.status == '1') {
                api.columns([5]).visible(showColumn);
            }
        },
        drawCallback: function (settings) {
            $('.rating').each(function () {
                $('<span class="badge badge-info" style="font-size: 10px;"></span>')
                    .text($(this).val() || ' ')
                    .insertAfter(this);
            });
        }
    });

    $('#user_transaction_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/transaction/data",
            type: 'post',
            dataType: "json",
            data: {
                end_date: function () { return $('#end_date').val() },
                id: function () { return $('#user_id').val() },
                provider: function () { return $('#provider').val() },
                start_date: function () { return $('#start_date').val() }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user_name', name: 'HCP Provider' },
            { data: 'client_name', name: 'Patient name' },
            { data: 'transaction_data', name: 'transaction_data' },
            { data: 'transaction_date', name: 'Transaction date' },
            { data: 'transaction_type', name: 'Transaction Type' },
            { data: 'amount', name: 'Amount' },
            { data: 'payout_amount', name: 'Payout Amount' },
            { data: 'payment_type', name: 'Payment Type' },
            { data: 'status', name: 'Status' },
            { data: 'payout_status', name: 'Payout Status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            if ($('#provider').val() == 'patients') {
                api.columns([0]).visible(showColumn);
                api.columns([7]).visible(showColumn);
                // api.columns([6]).visible(showColumn);
                api.columns([10]).visible(showColumn);
            } else {
                api.columns([0]).visible(showColumn);
                api.columns([5]).visible(showColumn);
                api.columns([8]).visible(showColumn);
                api.columns([9]).visible(showColumn);
            }
        },
        drawCallback: function (settings) {
            walletBalanceGet();
        }
    });

    $('#shop_medicine_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/medicine/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'medicine_detail', name: 'medicine_detail' },
            { data: 'medicine_sku', name: 'medicine_sku' },
            { data: 'capsual_quantity', name: 'capsual_quantity' },
            { data: 'mrp_price', name: 'mrp_price' },
            { data: 'medicine_type', name: 'medicine_type' },
            { data: 'status', name: 'status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

    $('#service_laboratories_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/services/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'service_detail', name: 'service_detail' },
            { data: 'service_charge', name: 'service_charge' },
            { data: 'service_charge_type', name: 'service_charge_type' },
            { data: 'status', name: 'status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
            if (data_obj.provider == 'laboratories') {
                api.columns([2]).visible(showColumn);
            }
        }
    });
    // var segment_str = window.location.pathname;
    // var segment_array = segment_str.split('/');
    // var last_segment = segment_array.pop();
    // $('#user_review_datatable').DataTable({
    //     lengthChange: true,
    //     processing: true,
    //     serverSide: true,
    //     bPaginate: true,
    //     // responsive: true,
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


    $('#transaction-date-range').daterangepicker({
        startDate: moment().subtract(30, 'days'),
        endDate: moment(),
        maxDate: moment()
    });
    $('#transaction-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        var oTable = $('#user_transaction_datatable').dataTable();
        oTable.fnDraw(false);
        // $('#user_transaction_datatable').DataTable().ajax.reload();
    });
});




function walletBalanceGet() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: user_url + "/wallet_balance",
        type: "post",
        dataType: 'json',
        data: {
            end_date: $('#end_date').val(),
            id: $('#user_id').val(),
            provider: $('#provider').val(),
            start_date: $('#start_date').val()
        },
        success: function (data) {
            if (data.status) {
                $('#total_balance').text(data.data);
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
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
                        toastr.success(data.msg, App_name_global);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
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
                        toastr.success(data.msg, App_name_global);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}