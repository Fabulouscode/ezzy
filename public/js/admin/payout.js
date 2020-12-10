
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
            url: payout_url + '/data',
            type: 'post',
            dataType: "json",
            data: payout_obj,
            async: true
        },
        columns: [
            { data: 'user_name', name: 'User Name' },
            { data: 'service_provider', name: 'Service Provider' },
            { data: 'amount', name: 'Amount' },
            { data: 'fees_charge', name: 'Deduction' },
            { data: 'payout_amount', name: 'Payout Amount' },
            { data: 'payout_status', name: 'Status' },
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

    $("#select_all").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });


    $(document).on('submit', '#payout_amount_form', function (event) {
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url + '/transaction',
            data: $('#payout_amount_form').serialize(),
            success: function (response) {
                $('#user_id').val('');
                $('#bank_transaction_id').val('');
                $('#notes').val('');
                $('#addPayoutAmount').modal('hide');
                var oTable = $('#payout_datatable').dataTable();
                oTable.fnDraw(false);
                toastr.success(response.msg, 'EzzyCare App');
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                $.each(myArr.errors, function (index, value) {
                    toastr.error(value, 'Vyzum App');
                });
                return false;
            },
        });
        return false;
    });
});

function editRow(id) {
    console.log($(this).attr('data-amount'));
    $("form[name='payout_amount_form']").parsley().destroy();
    $("form[name='payout_amount_form']").parsley();
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
            url: payout_url + '/paid',
            type: "post",
            dataType: 'json',
            data: { 'transaction_ids': payout_transaction },
            success: function (data) {

                toastr.success(data.msg, 'EzzyCare App');
                var oTable = $('#payout_datatable').dataTable();
                oTable.fnDraw(false);
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, 'EzzyCare App');
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

            toastr.success(data.msg, 'EzzyCare App');
            var oTable = $('#payout_datatable').dataTable();
            oTable.fnDraw(false);
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, 'EzzyCare App');
        }
    });
}    