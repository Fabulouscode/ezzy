
$(function () {
    // $('#contact_start_date').val(moment().subtract(1, 'years').format("YYYY-MM-DD"));
    // $('#contact_end_date').val(moment().format("YYYY-MM-DD"));

    $('#contact_form_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: contact_form_url,
            type: 'get',
            dataType: "json",
            async: true,
            data: {
                start_date: function () { return $('#contact_start_date').val() },
                end_date: function () { return $('#contact_end_date').val() }                  
            }
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'country', name: 'country' },
            { data: 'mobile', name: 'mobile' },
            { data: 'subject', name: 'subject' },
            { data: 'read', name: 'read' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        },
        drawCallback: function (settings) {
        }
    });

    $('#contact-date-range').daterangepicker({
        // startDate: moment().subtract(1, 'years'),
        // endDate: moment(),
        maxDate: moment(),
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        alwaysShowCalendars: true,
        opens: "right",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#contact-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#contact_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#contact_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#contact_form_datatable').dataTable();
        oTable.fnDraw(true);
    });

    if(contactFormId && contactFormId != ''){
        ContactFormRead(contactFormId);
    }


});


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
                    url: contact_form_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#contact_form_datatable').dataTable();
                        oTable.fnDraw(true);
                        toastr.success(data.msg, App_name_global);
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}


function ContactFormRead(contactFormID) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: contact_form_url + '/' + contactFormID,
        type: 'put',
        processData: false,
        contentType: false,
        success: function(result) {

        },
        error: function(jqXHR, textStatus, errorThrown) {
            var myArr = jqXHR.responseJSON.errors;
            $.each(myArr, function(index, value) {
                toastr.error(value, appName);
            });
        }
    });
}
