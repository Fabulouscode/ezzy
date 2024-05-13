
$(function () {
    $("form[name='user_form']").parsley();
    var url_string = window.location.href;
    var url = new URL(url_string);
    var searchHCPtype = url.searchParams.get("hcp_type");
    // $('#user_start_date').val(moment().subtract(1, 'years').format("YYYY-MM-DD"));
    // $('#user_end_date').val(moment().format("YYYY-MM-DD"));

    $('#user_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        search: { "search": searchHCPtype },
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: {
                status: data_status,
                category_id: data_category_id,
                provider: data_provider,
                filter_status: function () { return $('#searchByStatus').val() },
                subcategory_id: function () { return $('#searchByHcpType').val() },
                completed_progress: function () { return $('#searchByHcpTypeProgress').val() },
                country_id: function () { return $('#searchByCountry').val() },
                city_id: function () { return $('#searchByCity').val() },
                address: function () { return $('#searchByAddress').val() },
                start_date: function () { return $('#user_start_date').val() },
                end_date: function () { return $('#user_end_date').val() },
                user_approved_start_date: function () { return $('#user_approved_start_date').val() },
                user_approved_end_date: function () { return $('#user_approved_end_date').val() },
                birth_start_date: function () { return $('#user_birth_start_date').val() },
                birth_end_date: function () { return $('#user_birth_end_date').val() },
                dob_month: function () { return $('#datepicker-month').val() },
                dob_year: function () { return $('#datepicker-year').val() },
            },
            complete: function () {
                $('#ajax_loader').hide();
            },
        },
        columns: [
            { data: 'id', name: 'users.id', searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'email', name: 'users.email' },
            { data: 'mobile_no', name: 'mobile_no' },
            { data: 'wallet_balance', name: 'wallet_balance' },
            { data: 'hcp_type', name: 'hcp_type' },
            { data: 'created_at', name: 'users.created_at', searchable: false },
            { data: 'dob', name: 'dob', searchable: false },
            // { data: 'practicing_licence_date', name: 'practicing_licence_date', searchable: false },
            {
                data: '', name: 'Ratings', orderable: false, searchable: false,
                render: function (data, type, row) {
                    var rating_count = '0';
                    if (data_obj.category_id == '2') {
                        if (row.user_order_rating != '' && row.user_order_rating != null) {
                            rating_count = row.user_order_rating;
                        }
                    } else {
                        if (row.user_appointment_rating != '' && row.user_appointment_rating != null) {
                            rating_count = row.user_appointment_rating;
                        }
                    }

                    if (rating_count == '' || rating_count == null) {
                        rating_count = '0';
                    }

                    rating_count = parseFloat(rating_count).toFixed(2);

                    return '<input type="hidden" class="rating" data-filled="mdi mdi-star font-20 text-primary" data-empty="mdi mdi-star-outline font-20 text-muted" data-readonly value = "' + rating_count + '" />';
                }
            },
            { data: 'completed_percentage', name: 'completed_percentage' },
            { data: 'status', name: 'status' },
            // { data: 'actiondetails', name: 'actiondetails', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
            if (data_obj.category_id != '') {
                api.columns([4]).visible(showColumn);
                api.columns([7]).visible(showColumn);
            }
            if (data_obj.category_id == '') {
                api.columns([5]).visible(showColumn);
            }
            if (data_obj.status == '1') {
                api.columns([8]).visible(showColumn);
            }
            if (data_obj.status != '1') {
                api.columns([9]).visible(showColumn);
            }
            // if (data_obj.provider != "healthcare") {
            //     api.columns([6]).visible(showColumn);
            // }
            // console.log(data_obj);
        },
        drawCallback: function (settings) {
            $('.rating').each(function () {
                $('<span class="badge badge-info" style="font-size: 10px;"></span>')
                    .text($(this).val() || ' ')
                    .insertAfter(this);
            });
        }
    });

    $('#user_transaction_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/transaction/data",
            type: 'post',
            dataType: "json",
            data: {
                end_date: function () { return $('#end_date').val() },
                id: function () { return $('#user_id').val() },
                provider: function () { return $('#provider').val() },
                start_date: function () { return $('#start_date').val() }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user_name', name: 'HCP Provider' },
            { data: 'client_name', name: 'Patient name' },
            { data: 'transaction_data', name: 'transaction_data' },
            { data: 'transaction_date', name: 'Transaction date' },
            { data: 'transaction_type', name: 'Transaction Type' },
            { data: 'amount', name: 'Amount' },
            { data: 'payout_amount', name: 'Payout Amount' },
            { data: 'payment_type', name: 'Payment Type' },
            { data: 'status', name: 'Status' },
            { data: 'payout_status', name: 'Payout Status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            if ($('#provider').val() == 'patients') {
                api.columns([0]).visible(showColumn);
                api.columns([7]).visible(showColumn);
                // api.columns([6]).visible(showColumn);
                api.columns([10]).visible(showColumn);
            } else {
                api.columns([0]).visible(showColumn);
                api.columns([5]).visible(showColumn);
                api.columns([8]).visible(showColumn);
                api.columns([9]).visible(showColumn);
            }
        }
    });

    $('#shop_medicine_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/medicine/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'medicine_detail', name: 'medicine_detail' },
            { data: 'medicine_sku', name: 'medicine_sku' },
            { data: 'capsual_quantity', name: 'capsual_quantity' },
            { data: 'mrp_price', name: 'mrp_price' },
            { data: 'medicine_type', name: 'medicine_type' },
            { data: 'status', name: 'status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
        }
    });

    $('#service_laboratories_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: user_url + "/services/data",
            type: 'post',
            dataType: "json",
            async: true,
            data: data_obj
        },
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'service_detail', name: 'service_detail' },
            { data: 'service_charge', name: 'service_charge' },
            { data: 'service_charge_type', name: 'service_charge_type' },
            { data: 'status', name: 'status' },
        ],
        order: [[0, 'desc']],
        initComplete: function (settings) {
            var api = new $.fn.dataTable.Api(settings);
            var showColumn = false;
            api.columns([0]).visible(showColumn);
            // if (data_obj.provider == 'laboratories') {
            //     api.columns([2]).visible(showColumn);
            // }
        }
    });
    // var segment_str = window.location.pathname;
    // var segment_array = segment_str.split('/');
    // var last_segment = segment_array.pop();
    // $('#user_review_datatable').DataTable({
    //     lengthChange: true,
    //     processing: true,
    //     serverSide: true,
    //     bPaginate: true,
    //     // responsive: true,
    //     ajax: {
    //         headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
    //         url: user_url + "/review/" + last_segment,
    //         type: 'get',
    //         dataType: "json",
    //         async: true,
    //     },
    //     columns: [
    //         { data: 'first_name', name: 'first_name' },
    //         { data: 'last_name', name: 'last_name' },
    //         { data: 'email', name: 'email' },
    //         { data: 'mobile_no', name: 'mobile_no' },
    //         { data: 'categoryParent', name: 'categoryParent.name' },
    //         { data: 'categoryChild', name: 'categoryChild.name' },
    //         { data: 'status', name: 'status' },
    //         { data: 'action', name: 'action', orderable: false, searchable: false },
    //     ],
    //     order: [[0, 'desc']]
    // });

    $('#searchByHcpType, #searchByStatus, #searchByHcpTypeProgress, #searchByCountry,#searchByCity,#searchByAddress').on('change', function (ev, picker) {
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#user-date-range').daterangepicker({
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
    $('#user-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#user_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#user_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });

    $('#user-approved-date-range').daterangepicker({
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
    $('#user-approved-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#user_approved_start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#user_approved_end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });

    if ($("#user-birth-date-range")) {
        $('#user-birth-date-range').daterangepicker({
            // startDate: moment().subtract(1, 'years'),
            // endDate: moment(),
            maxDate: moment(),
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            },
            alwaysShowCalendars: true,
            showDropdowns: true,
            minDate: '1950-01-01',
            maxDate: moment().endOf('month'),
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
        $('#user-birth-date-range').on('apply.daterangepicker', function (ev, picker) {
            $('#user_birth_start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#user_birth_end_date').val(picker.endDate.format('YYYY-MM-DD'));
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        });
    }
    $("#datepicker-month").datepicker({
        format: "mm",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    }).on('changeDate', function () {
        // let month = $
        $(this).val();
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });


    $("#datepicker-year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        orientation: "left bottom",
        autoclose: true
    }).on('changeDate', function () {
        // let month = $
        $(this).val();
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(true);
    });
    //   $(document).ready(function () {
    //     $('#user-birth-date-range').daterangepicker({
    //       autoUpdateInput: false,
    //       locale: {
    //         cancelLabel: 'Clear'
    //       },
    //       showDropdowns: true,
    //       minDate: '1950-01-01',
    //       maxDate: moment().endOf('month')
    //     });

    //     $('#user-birth-date-range').on('apply.daterangepicker', function (ev, picker) {
    //       $(this).val(picker.startDate.format('MMMM YYYY'));
    //     });

    //     $('#user-birth-date-range').on('cancel.daterangepicker', function () {
    //       $(this).val('');
    //     });

    //     $('#searchButton').on('click', function () {
    //       var selectedMonthYear = $('#user-birth-date-range').val();

    //       // You can use the selectedMonthYear value in your search logic
    //       console.log('Search for: ' + selectedMonthYear);
    //     });
    //   });




    $('#transaction-date-range').daterangepicker({
        // startDate: moment().subtract(30, 'days'),
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
    $('#transaction-date-range').on('apply.daterangepicker', function (ev, picker) {
        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        var oTable = $('#user_transaction_datatable').dataTable();
        oTable.fnDraw(true);
        // $('#user_transaction_datatable').DataTable().ajax.reload();
    });
});




function walletBalanceGet() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: pending_hcp_url + "/wallet_balance",
        type: "post",
        dataType: 'json',
        data: {
            end_date: $('#end_date').val(),
            id: $('#user_id').val(),
            provider: $('#provider').val(),
            start_date: $('#start_date').val()
        },
        success: function (data) {
            if (data.status) {
                $('#total_balance').text(data.data);
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
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
                    url: user_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
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

function changeStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        var oTable = $('#user_datatable').dataTable();
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

function changeHealthcareStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        window.location.replace(base_url + "/healthcare/user");
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function changeLaboratoriesStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        window.location.replace(base_url + "/laboratories/user");
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function changePharmacyStatusRow(row_id, status) {
    if (row_id) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to Change User Status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            confirmButtonText: 'Yes, Change Status it!'
        }).then(function () {
            if (row_id) {
                $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: user_url + "/change_status",
                    type: "post",
                    data: { 'user_id': row_id, 'status': status },
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Status!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        window.location.replace(base_url + "/pharmacy/user");
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.msg, App_name_global);
                    }
                });
            }
        });
    }
}

function fileValidation(id_name) {
    var fileInput = document.getElementById(id_name);
    var filePath = fileInput.value;
    // Allowing file type
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type');
        fileInput.value = '';
        return false;
    } else {
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + id_name + 'Preview').empty();
                document.getElementById(
                    id_name + 'Preview').innerHTML =
                    '<img src="' + e.target.result
                    + '" height="100px" width="100px"/>';
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}
function exportExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: patient_details_url + '/export',
        type: "post",
        dataType: 'json',
        data: {
            date_range: dateRange,
            filter_status: function () { return $('#searchByStatus').val() },
            birth_start_date: function () { return $('#user_birth_start_date').val() },
            birth_end_date: function () { return $('#user_birth_end_date').val() },
            dob_month: function () { return $('#datepicker-month').val() },
            dob_year: function () { return $('#datepicker-year').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },

        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportPendingHCPExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: pending_hcp_url + '/hcp_export',
        type: "post",
        dataType: 'json',
        data: {
            date_range:dateRange,
            subcategory_id: function () { return $('#searchByHcpType').val() },
            completed_progress: function () { return $('#searchByHcpTypeProgress').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportApprovedHCPExcel() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: approved_details_url + '/hcp_export',
        type: "post",
        dataType: 'json',
        data: {
            user_start_date: function () { return $('#user_start_date').val() },
            user_end_date: function () { return $('#user_end_date').val() },
            user_approved_start_date: function () { return $('#user_approved_start_date').val() },
            user_approved_end_date: function () { return $('#user_approved_end_date').val() },
            subcategory_id: function () { return $('#searchByHcpType').val() },
            filter_status: function () { return $('#searchByStatus').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportPendingPharmacistExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: pending_pharma_url + '/pharma_export',
        type: "post",
        dataType: 'json',
        data: {
            date_range: dateRange,
            completed_progress: function () { return $('#searchByHcpTypeProgress').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportApprovedPharmacistExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: approved_pharma_details_url + '/pharma_export',
        type: "post",
        dataType: 'json',
        data: {
            date_range: dateRange,
            filter_status: function () { return $('#searchByStatus').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportPendingLaboratoriesExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: pending_lab_url + '/lab_export',
        type: "post",
        dataType: 'json',
        data: {
            date_range: dateRange,
            subcategory_id: function () { return $('#searchByHcpType').val() },
            completed_progress: function () { return $('#searchByHcpTypeProgress').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}

function exportApprovedLaboratoriesExcel() {
    let dateRange = $('#user-date-range').val();
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: approved_lab_details_url + '/lab_export',
        type: "post",
        dataType: 'json',
        data: {
            date_range:dateRange,
            subcategory_id: function () { return $('#searchByHcpType').val() },
            filter_status: function () { return $('#searchByStatus').val() },
            city_id: function () { return $('#searchByCity').val() },
        },
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#user_datatable').dataTable();
            oTable.fnDraw(true);
        },
        beforeSend: function () {
            $('#ajax_loader').show();
        },
        complete: function () {
            $('#ajax_loader').hide();
        },
        error: function (error) {
            toastr.error(error.responseJSON.msg, App_name_global);
        }
    });
}