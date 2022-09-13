
$(document).on('change', '#category_id', function () {
    var category_id = $(this).val();
    $('#status').fadeIn();
    $('#preloader').fadeIn('slow');
    // $('body').delay(350).css({
    //     'overflow': 'visible'
    // });
    $.ajax({
        type: 'post',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: user_wallet_url,
        data: { category_id: category_id },
        success: function (response) {
            var html = '';
            $.each(response.data, function (k, v) {
                html += `<option value="` + v.id + `">` + (v.first_name != null ? v.first_name : '') + ' ' + (v.last_name != null ? v.last_name : '') + ' (' + v.email + ')'+`</option>`;
            });
            $('#user_id').html(html);
            $('#status').fadeOut();
            $('#preloader').delay(350).fadeOut('slow');
            $('body').delay(350).css({
                'overflow': 'visible'
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var myArr = JSON.parse(jqXHR.responseText);
            $.each(myArr.errors, function (index, value) {
                toastr.error(value, App_name_global);
            });
            return false;
        },
    });
});