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
                'status': ['5','6'],
                'hacp_type': '1',
            }
        },
        columns: [
            { data: 'id', name: 'appointments.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
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
                'status': ['5','6'],
                'hacp_type': '3',
            }
        },
        columns: [
            { data: 'id', name: 'appointments.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'appointment_type', name: 'appointment_type' },
            { data: 'appointment_date', name: 'appointment_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
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
            { data: 'id', name: 'orders.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'service_provider', name: 'service_provider' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
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
    getAppointmentAndOrderEarning();
    $('#earning-chart-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#earning_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#earning_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        getAppointmentAndOrderEarning();
    });

    $('.peity-donut').each(function () {
        $(this).peity("donut", $(this).data());
    });
    $('.peity-pie').each(function () {
        $(this).peity("pie", $(this).data());
    });

});

function createBarChart(data, xkey, ykeys) {
    $("#morris-revenue-bar-chart").empty();
    var months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    if (data.length > 0) {
        Morris.Bar({
            element: 'morris-revenue-bar-chart',
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            hoverCallback: function (index, options, content, row) {
                var hover = "";
                hover += "<div class='morris-hover-row-label'>" + months[row.month] + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#508aeb;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>Total Income: </b>₦ " + row.total_income.toLocaleString('en-US', { maximumFractionDigits: 2 }) + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#fcc24c;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>Total Payout: </b>₦ " + row.total_payout.toLocaleString('en-US', { maximumFractionDigits: 2 }) + "</div>";
                return hover;
                // return (content);
            },
            xLabelFormat: function (x) {
                return months[x.src.month];
            },
            labels: ['Total Income', 'Total Payout'],
            gridLineColor: '#eef0f2',
            barSizeRatio: 0.4,
            resize: true,
            hideHover: 'auto',
            barColors: ['#508aeb', '#fcc24c']
        });
    }
}


function createAreaChart(data) {
    $("#morris-count-area-chart").empty();
    if (data.length > 0) {
        Morris.Area({
            element: 'morris-count-area-chart',
            data: data,
            xkey: 'date',
            parseTime: false,
            ykeys: ['hcp_count', 'order_count', 'lab_count', 'treatment_plan_count'],
            hoverCallback: function (index, options, content, row) {
                var hover = "";
                hover += "<div class='morris-hover-row-label'>" + row.date + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#ff5560;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>HCP Appointments: </b> " + row.hcp_count + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#fcc24c;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>Pharmacy Orders: </b> " + row.order_count + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#508aeb;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>Laboratories Appointments: </b> " + row.lab_count + "</div>";
                hover += "<div class='morris-hover-point' style='color: #A4ADD3'><span style='background-color:#28b16d;margin:2px 2px 0px 0px;padding:0px 5px 0px 10px;'></span><b>Treatment Plan: </b> " + row.treatment_plan_count + "</div>";
                return hover;
                // return (content);
            },
            labels: ['HCP Appointments', 'Pharmacy Orders', 'Laboratories Appointments', 'Treatment Plan'],
            lineColors: ['#ff5560', '#fcc24c', '#508aeb', '#28b16d'],
            hideHover: 'auto',
            gridIntegers: true,
            ymin: 0
        });
    }
}


function getAreaChart() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: dashboard_url + '/chart/revenue',
        type: "post",
        dataType: 'json',
        data: { 'start_date': $('#count_start_date').val(), 'end_date': $('#count_end_date').val() },
        success: function (data) {
            if (data.status) {
                var response = data.data;
                createAreaChart(response);
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}


//bar chart Revanu
function getBarChart() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: dashboard_url + '/chart/income',
        type: "post",
        dataType: 'json',
        data: { 'start_date': $('#revenue_start_date').val(), 'end_date': $('#revenue_end_date').val() },
        success: function (data) {
            if (data.status) {
                var response = data.data;
                createBarChart(response.chart_data, 'month', ['total_income', 'total_payout']);
                $('#total_income').text(response.total_income);
                $('#total_payout').text(response.total_payout);
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}



//order and appointment wise earning 
function getAppointmentAndOrderEarning() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: dashboard_url + '/chart/earning',
        type: "post",
        dataType: 'json',
        data: { 'start_date': $('#earning_start_date').val(), 'end_date': $('#earning_end_date').val() },
        success: function (data) {
            if (data.status) {
                var response = data.data;
                $('#ezzycare_earning').text(response.ezzycare_earning);
                $('#orders_earning').text(response.order_paid + ',' + response.order_pending);
                $('#orders_earning').peity("donut", $('#orders_earning').data());
                $('#appointments_earning').text(response.appointment_paid + ',' + response.appointment_pending);
                $('#appointments_earning').peity("donut", $('#appointments_earning').data());
                $('#treatment_plan_earning').text(response.treatment_plan_paid + ',' + response.treatment_plan_pending);
                $('#treatment_plan_earning').peity("donut", $('#treatment_plan_earning').data());
                $('#appointments_order_treatment_earning').text(response.appointments_and_order_paid + '/' + response.appointments_and_order_total);
                $('#appointments_order_treatment_earning').peity("pie", $('#appointments_order_treatment_earning').data());
                $('#appointments_percentage').text(parseInt(response.appointments_percentage));
                $('#orders_percentage').text(parseInt(response.orders_percentage));
                $('#treatment_plan_percentage').text(parseInt(response.treatment_plan_percentage));
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}