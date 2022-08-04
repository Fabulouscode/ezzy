
$(function () {
    $("form[name='appointment_form']").parsley();
    // $('#start_date').val(moment().subtract(3, 'months').format("YYYY-MM-DD"));
    // $('#end_date').val(moment().format("YYYY-MM-DD"));

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
                status: (data_status != '') ? data_status : function () { return $('#searchByStatus').val() },
                filter_status: function () { return $('#searchByStatus').val() },
                category_id: function () { return $('#searchByHcpType').val() },     
                end_date: function () { return $('#end_date').val() },
                start_date: function () { return $('#start_date').val() },    
                appointment_type: function () { return $('#searchByAppointmentType').val() },    
                urgent: function () { return $('#searchByAppointmentUrgent').val() },    
                user_id: data_user_id   
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
        },
    });

    $('#appointment_review_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: appointment_url + '/reviews',
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'appointments.id', searchable: false },
            { data: 'appointment_no', name: 'appointment_no', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'patient_name', name: 'patient_name' },
            {
                data: 'user_rating', name: 'user_rating',
                render: function (data, type, row) {
                    if (data == '' || data == null) {
                        data = '0';
                    }
                    data = parseFloat(data).toFixed(2);
                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted" data-readonly value = "' + data + '" />';
                }
            },
            { data: 'user_review', name: 'user_review' },
        ],
        order: [[0, 'desc']],
        createdRow: function (row, data, dataIndex) {
            var ratingInput = $(row).find('.rating');
            $(ratingInput).rating();
        },
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        },
        drawCallback: function (settings) {
            $('.rating').each(function () {
                $('<span class="badge badge-info"></span>')
                    .text($(this).val() || ' ')
                    .insertAfter(this);
            });
        }
    });

    $('#searchByHcpType, #searchByStatus, #searchByAppointmentType, #searchByAppointmentUrgent').on('change', function (ev, picker) {
        var oTable = $('#appointments_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#appointment-date-range').daterangepicker({
        // startDate: moment().subtract(3, 'months'),
        // endDate: moment(),
        maxDate: moment(),
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        alwaysShowCalendars: true,
        opens: "center",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
    $('#appointment-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#appointments_datatable').dataTable();
        oTable.fnDraw(true);
        // $('#user_transaction_datatable').DataTable().ajax.reload();
    });

});

function updateAppointmentCancel(row_id) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Appointment Cancel it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: appointment_url + "/edit/cancel/" + row_id,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Appointment Cancel!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#appointments_datatable').dataTable();
                        oTable.fnDraw(true);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}


function deleteRow(row_id) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: appointment_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#appointments_datatable').dataTable();
                        oTable.fnDraw(true);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}