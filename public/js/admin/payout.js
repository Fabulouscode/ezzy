
$(function () {
    $("form[name='payout_amount_form']").parsley();
    $('#payout_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/data',
            type: 'post',
            dataType: "json",
            data: {
                payout_status : payout_status,
                category_id :  function () { return $('#searchByHcpType').val() },
            },
            async: true
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'user_name', name: 'user_name', title:'User Name' },
            { data: 'service_provider', name: 'service_provider', title: 'Service Provider', orderable: false, searchable: false },
            { data: 'bank_details', name: 'bank_details', title: 'Bank Details', orderable: false, searchable: false },
            { data: 'amount', name: 'amount', title: 'Amount', orderable: false, searchable: false  },
            { data: 'fees_charge', name: 'fees_charge', title: 'Deduction', orderable: false, searchable: false  },
            { data: 'payout_amount', name: 'payout_amount', title: 'Payout Amount', orderable: false, searchable: false  },
            { data: 'payout_status', name: 'payout_status', title: 'Status' },
            { data: 'action', name: 'Action', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        },
        drawCallback: function (settings) {
            $('#select_all').prop('checked', false);
        }
    });

    $('#payout_paid_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: {
                category_id :  function () { return $('#searchByHcpType').val() },
            },
        },
        columns: [
            { data: 'user_name', name: 'user_name', title: 'User Name' },
            { data: 'service_provider', name: 'Service Provider', orderable: false, searchable: false },
            { data: 'amount', name: 'amount', title: 'Amount' },
            { data: 'deduction_amount', name: 'deduction_amount', title: 'Deduction' },
            { data: 'payable_amount', name: 'payable_amount', title: 'Payout Amount' },
            { data: 'action', name: 'Action', orderable: false, searchable: false }
        ],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        },
        drawCallback: function (settings) {
            $('#select_all').prop('checked', false);
        }
    });

    $('#payout_history_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/transaction',
            type: 'get',
            dataType: "json",
            async: true,
            data: payout_history,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'User Name' },
            { data: 'amount', name: 'Amount' },
            { data: 'deduction_amount', name: 'Deduction' },
            { data: 'payable_amount', name: 'Payout Amount' },
            { data: 'bank_transaction_id', name: 'Transaction Id' },
            { data: 'admin_name', name: 'Approved Name' },
            { data: 'approved_date', name: 'Approved Date' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        },
        drawCallback: function (settings) {
            $('#select_all').prop('checked', false);
        }
    });

    $('#transaction_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/transaction/data',
            type: 'post',
            dataType: "json",
            data: {
                category_id :  function () { return $('#searchByHcpTypeTransaction').val() },
                end_date: function () { return $('#end_date').val() },
                start_date: function () { return $('#start_date').val() },
                transaction_msg: function () { return $('#searchByTransactionMSG').val() }
            },
            async: true
        },
        columns: [
            { data: 'id', name: 'id', searchable:false },
            { data: 'user_name', name: 'user_name', title: 'User Name' },
            { data: 'service_provider', name: 'service_provider', title: 'Service Provider'},
            { data: 'transaction_msg', name: 'transaction_msg', title: 'transaction_msg' },
            { data: 'transaction_date', name: 'transaction_date', title: 'transaction_date',searchable:false },
            { data: 'fees_charge', name: 'fees_charge', title: 'Fees Charge' },
            { data: 'payout_amount', name: 'payout_amount', title: 'Payout amount' },
            { data: 'amount', name: 'amount', title: 'Total Charge'},
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
            // getHealthcareProviders();
        },
        drawCallback: function (settings) {
            getHealthcareProviders();
        }
    });

    $("#select_all").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(document).on('submit', '#payout_amount_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/paid',
            data: $('#payout_amount_form').serialize(),
            success: function (response) {
                $('#user_id').val('');
                $('#amount').val('');
                $('#deduction').val('');
                $('#payout_amount').val('');
                $('#bank_transaction_id').val('');
                $('#notes').val('');
                $('#addPayoutAmount').modal('hide');
                var oTable = $('#payout_datatable').dataTable();
                oTable.fnDraw(true);
                toastr.success(response.msg, App_name_global);
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                toastr.error(myArr.msg, App_name_global);
                return false;
            },
        });
        return false;
    });

    $('#searchByHcpType').on('change', function (ev, picker) {
        var oTable = $('#payout_paid_datatable').dataTable();
        oTable.fnDraw(true);
        var oTable = $('#payout_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#searchByHcpTypeTransaction, #searchByTransactionMSG').on('change', function (ev, picker) {
        var oTable = $('#transaction_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#user-date-range').daterangepicker({
        // startDate: moment().subtract(1, 'years'),
        // endDate: moment(),
        maxDate: moment(),
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        alwaysShowCalendars: true,
        opens: "right",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
    $('#user-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#user_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#user_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#transaction_datatable').dataTable();
        oTable.fnDraw(true);
    });
});

function editRow(event) {
    var user_id = $(event).attr('data-user_id');
    var amount = $(event).attr('data-amount');
    var deduction = $(event).attr('data-deduction');
    var payout_amount = $(event).attr('data-payout_amount');
    $("form[name='payout_amount_form']").parsley().destroy();
    $("form[name='payout_amount_form']").parsley();
    $('#user_id').val(user_id);
    $('#amount').val(amount);
    $('#deduction').val(deduction);
    $('#payout_amount').val(payout_amount);
    $('.modal-title').text('Add Transaction Details');
    $('#submit_btn').text('Add');
    setTimeout(function () {
        $('#bank_transaction_id').focus();
    }, 1000);
    $('#addPayoutAmount').modal();
}

function payoutUser(user_id) {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: payout_url + '/status/user/'+user_id,
        type: "get",
        dataType: 'json',
        success: function (response, textStatus, request) {
            toastr.success(response.msg, App_name_global);
            var oTable = $('#payout_datatable').dataTable();
            oTable.fnDraw(true);
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function payout() {
    payout_transaction = [];

    $("input:checkbox").each(function () {
        if ($(this).is(":checked")) {
            if ($(this).val()) {
                payout_transaction.push($(this).val());
            }
        }
    });
    if (payout_transaction.length == '0') {
        swal({
            type: 'error',
            title: 'Oops...',
            text: 'Please select at least one!',
            footer: ''
        })
        return true;
    }
    if (payout_transaction) {
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/status',
            type: "post",
            dataType: 'json',
            data: { 'transaction_ids': payout_transaction },
            success: function (response, textStatus, request) {
                toastr.success(response.msg, App_name_global);
                var oTable = $('#payout_datatable').dataTable();
                oTable.fnDraw(true);
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }
}

function Export() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: payout_url + '/export',
        type: "get",
        dataType: 'json',
        success: function (data) {

            toastr.success(data.msg, App_name_global);
            var oTable = $('#payout_datatable').dataTable();
            oTable.fnDraw(true);
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportExcel() {
    payout_transaction = [];

    $("input:checkbox").each(function () {
        if ($(this).is(":checked")) {
            if ($(this).val()) {
                payout_transaction.push($(this).val());
            }
        }
    });

    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: payout_url + '/export',
        type: "post",
        dataType: 'json',
        data: { 'transaction_ids': payout_transaction },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#payout_datatable').dataTable();
            oTable.fnDraw(true);
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
    
}

function getHealthcareProviders() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: payout_url + '/transaction/payout/calculate',
        type: 'post',
        dataType: "json",
        data: {
            category_id :  function () { return $('#searchByHcpTypeTransaction').val() },
            end_date: function () { return $('#end_date').val() },
            start_date: function () { return $('#start_date').val() },            
            transaction_msg: function () { return $('#searchByTransactionMSG').val() }
        },
        async: true,
        success: function (data) {
            if(data != ''){
                $('#transactionTotal').text(Number(data.amount).toFixed(2));
                $('#transactionPayout').text(Number(data.payout_amount).toFixed(2));
                $('#transactionEzzyCare').text(Number(data.fees_charge).toFixed(2));
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}