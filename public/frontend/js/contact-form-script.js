$(function () {
    $("form[name='contactForm']").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 3
                },
                subject: {
                    required: true,
                    minlength: 3
                },
                mobile: {
                    required: true,
                    number: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name:  {
                    required: "Please enter your Name",
                },
                email: {
                    required: "Please enter your Email",
                    email: "Please enter a valid Email"
                },
                subject: {
                    required: "Please enter Subject",
                },
                subject: {
                    required: "Please enter mobile number",
                    number: "Please enter only numeric value",
                },
                message: {
                    required: "Please enter Message",
                }
            },
            submitHandler: function(form) {
                formSubmit();
            }
    });

    
    function formSubmit() {
        if (!$('#contactForm').valid()) {
            formError();
            return true;
        }
        $.ajax({
            data: $('#contactForm').serialize(),
            url: SITEURL + "/api/contact/form_submit",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                formSuccess();
                toastr.success('Contact form submit success', App_name_global);
            },
            error: function (error) {
                formError();
                toastr.error(error.responseJSON.msg, App_name_global);
            }
        });
    }

    function formSuccess() {
        $("#contactForm")[0].reset();
    }

    function formError() {
        $("#contactForm")
            .removeClass()
            .addClass("shake animated")
            .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                $(this).removeClass();
            });
    }
});
