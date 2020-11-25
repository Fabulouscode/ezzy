$(function () {
    //$("form[name='appointment_review_form']").parsley();
    $('#order_review_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: order_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'order_no', name: 'Order No' },
            { data: 'user_name', name: 'User name' },
            { data: 'patient_name', name: 'Patient name' },
            { data: 'user_rating', name: 'Ratings' },
            { data: 'user_review', name: 'Reviews' },
        ],
        order: [[0, 'desc']],
    });

});