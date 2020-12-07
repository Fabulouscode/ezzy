
$(function () {
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
            { data: 'service_provider', name: 'Service Provider' },
            { data: 'user_name', name: 'User Name' },
            { data: 'payout_date', name: 'Payout Date' },
            { data: 'payout_amount', name: 'Payout Amount' },
            { data: 'payout_status', name: 'Status' },
            { data: 'action', name: 'Action' },
        ],
        order: [[1, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            console.log(payout_obj);
            if (payout_obj.payout_status != '0') {
                api.columns([3]).visible(showColumn);
            } else {
                api.columns([0]).visible(showColumn);
            }
            api.columns([0]).visible(showColumn);
            api.columns([6]).visible(showColumn);
        },
        drawCallback: function (settings) {
            $('#select_all').prop('checked', false);
        }
    });

    $("#select_all").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
});


function payout() {
    payout_transaction = [];

    $("input:checkbox").each(function () {
        if ($(this).is(":checked")) {
            if ($(this).val()) {
                payout_transaction.push($(this).val());
            }
        }
    });

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