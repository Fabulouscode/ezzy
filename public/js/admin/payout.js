
$(function () {
    $('#payout_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: payout_url,
            type: 'get',
            dataType: "json",
            async: true
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'User name' },
            { data: 'transaction_date', name: 'Transaction date' },
            { data: 'amount', name: 'Amount' },
            { data: 'mode_of_payment', name: 'Mode of payment' },
            { data: 'transaction_type', name: 'Transaction type' },
            { data: 'status', name: 'Status' },
            { data: 'created_at', name: 'Created at' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });
});


