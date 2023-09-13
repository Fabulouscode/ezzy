
$(function () {
    $('#user_tracking_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_tracking_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user_type', name: 'user_type' },
            { data: 'admin_name', name: 'admin_name' },
            { data: 'user_name', name: 'user_name' },
            { data: 'field_name', name: 'field_name' },
            { data: 'field_value', name: 'field_value' },
            { data: 'created_at', name: 'created_at' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });
    $('#register_mobile_no_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_tracking_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'country_code', name: 'country_code' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'device_type', name: 'device_type' },
            { data: 'device_id', name: 'device_id' },
            { data: 'created_at', name: 'created_at' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });
});