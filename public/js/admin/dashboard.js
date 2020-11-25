$(function () {

    //appointment
    $('#appointments_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: appointment_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: appointment_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });

    //order
    $('#pharmacy_order_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: pharmacy_order_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: pharmacy_order_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });
});

