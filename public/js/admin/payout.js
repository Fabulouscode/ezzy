
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
            data: payout_obj,
            async: true
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'user_name', name: 'User Name' },
            { data: 'service_provider', name: 'Service Provider' },
            { data: 'bank_details', name: 'Bank Details' },
            { data: 'amount', name: 'Amount' },
            { data: 'fees_charge', name: 'Deduction' },
            { data: 'payout_amount', name: 'Payout Amount' },
            { data: 'payout_status', name: 'Status' },
            { data: 'action', name: 'Action' },
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
            async: true
        },
        columns: [
            { data: 'user_name', name: 'User Name' },
            { data: 'service_provider', name: 'Service Provider' },
            { data: 'amount', name: 'Amount' },
            { data: 'deduction_amount', name: 'Deduction' },
            { data: 'payable_amount', name: 'Payout Amount' },
            { data: 'action', name: 'Action' }
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
                oTable.fnDraw(false);
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
                var a = document.createElement("a");
                a.href = response.data.file;
                a.download = response.data.name;
                document.body.appendChild(a);
                a.click();
                a.remove();
                toastr.success(response.msg, App_name_global);
                var oTable = $('#payout_datatable').dataTable();
                oTable.fnDraw(false);
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
            oTable.fnDraw(false);
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}