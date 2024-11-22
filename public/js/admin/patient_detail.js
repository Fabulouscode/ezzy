$('#patient_detail_datatable').DataTable({
    lengthChange: true,
    processing: true,
    serverSide: true,
    bPaginate: true,
    // responsive: true,
    ajax: {
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: patient_detail_url,
        type: 'get',
        dataType: "json",
        async: true,
        data: {
            status: data_status,
            category_id: data_category_id,
        },
        error: function (xhr, status, error) {
            console.error("Error in AJAX request:", xhr.responseText);
        },
    },
    columns: [
        { data: 'id', name: 'users.id', searchable: false },
        { data: 'user_name', name: 'user_name' },
        { data: 'email', name: 'users.email' },
        { data: 'mobile_no', name: 'mobile_no' },
        { data: 'created_at', name: 'users.created_at', searchable: false },
        { data: 'total_appointments', name: 'total_appointments', searchable: false },
        { data: 'total_orders', name: 'total_orders', searchable: false },
    ],
    order: [[0, 'desc']],
    initComplete: function (settings) {
        var api = new $.fn.dataTable.Api(settings);
        var showColumn = false;
        api.columns([0]).visible(showColumn);
        // console.log(data_obj);
    },
});