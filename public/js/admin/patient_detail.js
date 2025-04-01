$("#patient_detail_datatable").DataTable({
    lengthChange: true,
    processing: true,
    serverSide: true,
    bPaginate: true,
    // responsive: true,
    ajax: {
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content")
        },
        url: patient_detail_url,
        type: "get",
        dataType: "json",
        async: true,
        data: {
            status: data_status,
            category_id: function () { return $('#searchByHcpType').val() },     
            appointment_type: function () { return $('#searchByAppointmentType').val() },    
            urgent: function () { return $('#searchByAppointmentUrgent').val() },  
            appointment_start_date: function() {
                return $("#appointment_start_date").val();
            },
            appointment_end_date: function() {
                return $("#appointment_end_date").val();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error in AJAX request:", xhr.responseText);
        }
    },
    columns: [
        { data: "id", name: "users.id", searchable: false },
        { data: "user_name", name: "user_name" },
        { data: "email", name: "users.email" },
        { data: "mobile_no", name: "mobile_no" },
        { data: "created_at", name: "users.created_at", searchable: false },
        {
            data: "total_appointments",
            name: "total_appointments",
            searchable: false
        },
        { data: "total_orders", name: "total_orders", searchable: false }
    ],
    order: [[0, "desc"]],
    initComplete: function(settings) {
        var api = new $.fn.dataTable.Api(settings);
        var showColumn = false;
        api.columns([0]).visible(showColumn);
    }
});
$("#appointment-date-range").daterangepicker({
    maxDate: moment(),
    autoUpdateInput: false,
    locale: {
        cancelLabel: "Clear"
    },
    alwaysShowCalendars: true,
    opens: "right",
    ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
            moment()
                .subtract(1, "month")
                .startOf("month"),
            moment()
                .subtract(1, "month")
                .endOf("month")
        ]
    }
});
$("#appointment-date-range").on("apply.daterangepicker", function(ev, picker) {
    $("#appointment_start_date").val(picker.startDate.format("YYYY-MM-DD"));
    $("#appointment_end_date").val(picker.endDate.format("YYYY-MM-DD"));
    $(this).val(
        picker.startDate.format("MM/DD/YYYY") +
            " - " +
            picker.endDate.format("MM/DD/YYYY")
    );
    var oTable = $("#patient_detail_datatable").dataTable();
    oTable.fnDraw(true);
});

$('#searchByHcpType,  #searchByAppointmentType, #searchByAppointmentUrgent').on('change', function (ev, picker) {
    var oTable = $('#patient_detail_datatable').dataTable();
    oTable.fnDraw(true);
});