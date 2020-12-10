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
            data: {
                'status': '',
                'hacp_type': '1',
            }
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
    });

    $('#laboratories_appointments_datatable').DataTable({
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
            data: {
                'status': '',
                'hacp_type': '3',
            }
        },
        columns: [
            // { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
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
            // { data: 'id', name: 'id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        //  order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
        }
    });


    $('#count_start_date').val(moment().subtract(5, 'months').startOf('month').format("YYYY-MM-DD"));
    $('#count_end_date').val(moment().format("YYYY-MM-DD"));
    $('#count-chart-date-range').daterangepicker({
        startDate: moment().subtract(5, 'months').startOf('month'),
        endDate: moment(),
        maxDate: moment()
    });
    getAreaChart();
    $('#count-chart-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#count_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#count_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        getAreaChart();
    });

    $('#revenue_start_date').val(moment().subtract(2, 'months').startOf('month').format("YYYY-MM-DD"));
    $('#revenue_end_date').val(moment().format("YYYY-MM-DD"));
    $('#revenue-chart-date-range').daterangepicker({
        startDate: moment().subtract(2, 'months').startOf('month'),
        endDate: moment(),
        maxDate: moment()
    });
    getBarChart();
    $('#revenue-chart-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#revenue_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#revenue_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        getBarChart();
    });

    $('#earning_start_date').val(moment().subtract(0, 'months').startOf('month').format("YYYY-MM-DD"));
    $('#earning_end_date').val(moment().format("YYYY-MM-DD"));
    $('#earning-chart-date-range').daterangepicker({
        startDate: moment().subtract(0, 'months').startOf('month'),
        endDate: moment(),
        maxDate: moment()
    });
    $('#earning-chart-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#earning_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#earning_end_date').val(picker.endDate.format('YYYY-MM-DD'));
    });


});


function getAreaChart() {
    var areaData = [
        { y: '2012', a: 0, b: 0, c: 0 },
        { y: '2013', a: 150, b: 45, c: 15 },
        { y: '2014', a: 60, b: 150, c: 195 },
        { y: '2015', a: 180, b: 36, c: 21 },
        { y: '2016', a: 90, b: 60, c: 360 },
        { y: '2017', a: 75, b: 240, c: 120 },
        { y: '2018', a: 30, b: 30, c: 30 }
    ];

    createAreaChart(areaData);
    // $.ajax({
    //     headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
    //     url: dashboard_url + '/chart/area',
    //     type: "post",
    //     dataType: 'json',
    //     data: { 'start_date': $('#count_start_date').val(), 'end_date': $('#count_end_date').val() },
    //     success: function (data) {
    //         if (data.status == 'true') {
    //             console.log(data);
    //             createAreaChart(areaData);
    //         }
    //     },
    //     error: function (error) {
    //         toastr.error(error.responseJSON.msg, 'EzzyCare App');
    //     }
    // });
}

function createAreaChart(data) {
    $("#morris-count-area-chart").empty();
    Morris.Area({
        element: 'morris-count-area-chart',
        data: data,
        xkey: 'y',
        parseTime: false,
        ykeys: ['a', 'b', 'c'],
        labels: ['HCP Appointments', 'Pharmacy Orders', 'Laboratories Appointments'],
        lineColors: ['#ff5560', '#fcc24c', '#508aeb'],
        hideHover: 'auto'
    });
}

//bar chart Revanu
function getBarChart() {
    var barData = [
        { y: 'Jan', a: 15, b: 65 },
        { y: 'Feb', a: 40, b: 40 },
        { y: 'Mar', a: 65, b: 85 },
        { y: 'Apr', a: 87, b: 105 },
        { y: 'May', a: 78, b: 90 },
    ];
    createBarChart(barData);
    // $.ajax({
    //     headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
    //     url: dashboard_url + '/chart/area',
    //     type: "post",
    //     dataType: 'json',
    //     data: { 'start_date': $('#count_start_date').val(), 'end_date': $('#count_end_date').val() },
    //     success: function (data) {
    //         if (data.status == 'true') {
    //             console.log(data);
    //             createAreaChart(areaData);
    //         }
    //     },
    //     error: function (error) {
    //         toastr.error(error.responseJSON.msg, 'EzzyCare App');
    //     }
    // });
}

function createBarChart(data) {
    $("#morris-count-bar-chart").empty();
    Morris.Bar({
        element: 'morris-revenue-bar-chart',
        data: data,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Total Income', 'Total Payout'],
        gridLineColor: '#eef0f2',
        barSizeRatio: 0.4,
        resize: true,
        hideHover: 'auto',
        barColors: ['#508aeb', '#fcc24c']
    });
}

