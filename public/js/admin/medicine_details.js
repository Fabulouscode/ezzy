
$(function () {
    $("form[name='medicine_details_form']").parsley();
    $("form[name='medicine_import_form']").parsley();
    $('#medicine_details_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        // responsive: true,
        ajax: {
            url: medicine_details_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'medicine_details.id', searchable: false },
            { data: 'medicine_name', name: 'medicine_details.medicine_name' },
            { data: 'medicine_sku', name: 'medicine_details.medicine_sku' },
            { data: 'medicine_category', name: 'medicine_category' },
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

    if ($('#medicine_category_id').val()) {
        changeCategory($('#medicine_category_id').val());
    }
    $('#medicine_category_id').on('change', function () {
        changeCategory($('#medicine_category_id').val());
    });

    $("#image-dropzone").sortable({
        items: '.dz-preview',
        cursor: 'grab',
        opacity: 0.5,
        containment: '#image-dropzone',
        distance: 20,
        tolerance: 'pointer',
        stop: function () {
            $('form').find('input[name="medicine_images[]"]').remove();
            $('#image-dropzone .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {
                var name = el.innerHTML;
                $('form').append('<input type="hidden" class="medicine_dropzone" name="medicine_images[]" value="' + uploadedImageMap[name] + '">');
            });
        }
    });
    $("#image-dropzone").disableSelection();

    $(document).on('submit', '#medicine_import_form', function (event) {
        var formData = new FormData($("#medicine_import_form")[0]);
        event.preventDefault();
        $.ajax({
            type: 'post',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: medicine_details_url + '/import',
            dataType: "json",
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                $('#medicine_file').val('');
                $('#addMedicineImport').modal('hide');
                var oTable = $('#medicine_details_datatable').dataTable();
                oTable.fnDraw(true);
                toastr.success(response.msg, App_name_global);
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var myArr = JSON.parse(jqXHR.responseText);
                $.each(myArr.errors, function (index, value) {
                    toastr.error(value, App_name_global);
                });
                return false;
            },
        });
        return false;
    });

});

Dropzone.options.imageDropzone = {
    url: file_upload_url,
    acceptedFiles: ".png, .jpeg, .jpg, .gif",
    maxFilesize: 2, // MB
    maxFiles: 5,
    addRemoveLinks: true,
    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
    success: function (file, response) {
        $('form').append('<input type="hidden" class="medicine_dropzone" name="medicine_images[]" value="' + response.name + '">');
        uploadedImageMap[file.name] = response.name;
    },
    removedfile: function (file) {
        file.previewElement.remove();
        var name = '';
        if (typeof file.file_name !== 'undefined') {
            name = file.file_name;
        } else {
            name = uploadedImageMap[file.name];
        }
        $('form').find('input[name="medicine_images[]"][value="' + name + '"]').remove();
        $.ajax({
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            url: file_remove_url,
            type: "post",
            dataType: 'json',
            data: { 'file_name': name },
            success: function (data) {
                toastr.success(data.msg, App_name_global);
            },
            error: function (error) {
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    },
    init: function () {
        if (medicine_images.length > 0) {
            for (var i in medicine_images) {
                var image_name = medicine_images[i]['product_image'].substring(medicine_images[i]['product_image'].lastIndexOf('/') + 1);
                var file = storage_url + '/' + medicine_images[i]['product_image'];
                var mockFile = { name: image_name, size: '1234' };
                this.emit("addedfile", mockFile);
                this.emit("thumbnail", mockFile, file);
                this.emit("complete", mockFile);
                uploadedImageMap[image_name] = medicine_images[i]['product_image'];
                $('form').append('<input type="hidden" class="medicine_dropzone" name="medicine_images[]" value="' + medicine_images[i]['product_image'] + '">');
            }

        }

        this.on("sending", function (file, xhr, formData) {
            formData.append("folder_name", "medicine_images");
        });
    }


}

function addImportRow() {
    $("form[name='medicine_import_form']").parsley().destroy();
    $("form[name='medicine_import_form']").parsley();
    $('#medicine_file').val('');
    $('#addMedicineImport').modal();
}

function addExportRows() {
    $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: medicine_details_url + '/export',
        type: "post",
        dataType: 'json',
        data: {},
        success: function (response) {
            var a = document.createElement("a");
            a.href = response.data.file;
            a.download = response.data.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            toastr.success(response.msg, App_name_global);
            var oTable = $('#medicine_details_datatable').dataTable();
            oTable.fnDraw(true);
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
                    url: medicine_details_url + "/" + row_id,
                    type: "delete",
                    dataType: 'json',
                    success: function (data) {
                        swal(
                            'Deleted!',
                            data.msg,
                            'success'
                        )
                        toastr.success(data.msg, App_name_global);
                        var oTable = $('#medicine_details_datatable').dataTable();
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

function changeCategory(cat_id) {
    $("#medicine_subcategoy_id").val('');
    $("#medicine_subcategoy_id  option").each(function () {
        if ($(this).attr('data-cat_id') == cat_id) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    if (medicine_subcategoy_id != '') {
        $("#medicine_subcategoy_id").val(medicine_subcategoy_id);
    }
}