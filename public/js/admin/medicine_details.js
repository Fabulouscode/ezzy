
$(function () {
    $("form[name='medicine_details_form']").parsley();
    $('#medicine_details_datatable').DataTable({
        lengthChange: true,
        processing: true,
        serverSide: true,
        bPaginate: true,
        responsive: true,
        ajax: {
            url: medicine_details_url,
            type: 'get',
            dataType: "json",
            async: true,
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'categoryParent', name: 'categoryParent' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
    });
    if ($('#medicine_category_id').val()) {
        changeCategory($('#medicine_category_id').val());
    }
    $('#medicine_category_id').on('change', function () {
        changeCategory($('#medicine_category_id').val());
    });


    var uploadedImageMap = {};
    Dropzone.autoDiscover = false;
    Dropzone.options.documentDropzone = {
        url: medicine_details_url,
        maxFilesize: 2, // MB
        maxFiles: 2,
        addRemoveLinks: true,
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        success: function (file, response) {
            // $('form').append('<input type="hidden" name="medicine_image[]" value="' + response.name + '">');
            $('form').append('<input type="hidden" name="medicine_image" value="' + response.name + '">');
            uploadedImageMap[file.name] = response.name;
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedImageMap[file.name];
            }
            // $('form').find('input[name="medicine_image[]"][value="' + name + '"]').remove();
            $('form').find('input[name="medicine_image"][value="' + name + '"]').remove();
        },
        init: function () {
            if (medicine_image) {
                // for (var i in medicine_image) {
                //     var file = medicine_image[i];
                //     this.options.addedfile.call(this, file);
                //     file.previewElement.classList.add('dz-complete');
                //     $('form').append('<input type="hidden" name="medicine_image[]" value="' + file.file_name + '">');
                // }
                var file = medicine_image;
                this.options.addedfile.call(this, file);
                file.previewElement.classList.add('dz-complete');
                $('form').append('<input type="hidden" name="medicine_image[]" value="' + file.file_name + '">');
            }
        }
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
            console.log(row_id);
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
                        toastr.success(data.msg, 'EzzyCare App');
                        var oTable = $('#medicine_details_datatable').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error(error.msg, 'EzzyCare App');
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
}