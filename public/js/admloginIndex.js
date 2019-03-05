(function ($) {
    'use strict';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // notification function
    function notify(title, message, type) {
        var stack_bar_top = { "dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0 };
        var notice = new PNotify({
            title: title,
            text: message,
            type: type,
            addclass: 'stack-bar-top',
            stack: stack_bar_top,
            width: "100%",
            delay: 500
        });
    }

    // pagination handling function
    function paginate_handling(paginatorData, currentUrl = null) {
        console.log(paginatorData);
        var first_page_url = paginatorData.first_page_url.replace(/\/create|\/edit|\/delete/gi, '');
        var next_page_url = null;
        var last_page_url = paginatorData.last_page_url.replace(/\/create|\/edit|\/delete/gi, '');
        var path_url = paginatorData.path.replace(/\/create|\/edit|\/delete/gi, '');
        var current_page = paginatorData.current_page;
        var last_page = paginatorData.last_page;
        var per_page = paginatorData.per_page;
        var total = paginatorData.total;
        var last_page_number = parseInt(last_page_url.slice(-1));

        if (paginatorData.next_page_url !== null) {
            next_page_url = paginatorData.next_page_url.replace(/\/create|\/edit|\/delete/gi, '');
        }

        //in case of deleting
        if (currentUrl) {
            var currUrlNumber;
            currUrlNumber = parseInt(currentUrl.slice(-1));

            if (currUrlNumber - last_page_number == 1) {
                window.location.href = last_page_url;
            } else {
                location.reload();
            }
        } else {
            // if more than per page results
            if (total > per_page) {
                //if result cant fit one pagination redirect to next
                if (total % per_page == 1) {

                    window.location.href = last_page_url;

                } else if (total % per_page != 1) {
                    // if result is being added from different page redirect
                    if (last_page_url != window.location.href) {
                        window.location.href = last_page_url;
                    } else {
                        location.reload();
                    }

                }
            } else {
                location.reload();
            }
        }




    }
    $('.modalDelete').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });

    $('.modalCreate').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });

    $('.modalEdit').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });

    //event when clicking on trash can
    $('.modalDelete').click(function () {
        var id = $(this).parent().siblings('.id').text();
        var email = $(this).parent().siblings('.email').text();

        $('#duid').val(id);
        $('#modalDelete').find('span.userEmail').text(email);
    });

    //event for clicking add button
    $(".modalCreate").click(function () {
        $(".modalForm").find("h2.panel-title").text("Add User");
        $("#confirm").attr('data', 'create');
        $(".modalForm").find('.form-control').val("");
    });

    // clicking on pencil icon for editing users
    $('.modalEdit').click(function () {
        $(".modalForm").find("h2.panel-title").text("Edit User");
        $("#confirm").attr('data', 'edit');
        var id = $(this).parent().siblings('.id').text();
        var name = $(this).parent().siblings('.name').text();
        var email = $(this).parent().siblings('.email').text();
        var country = $(this).parent().siblings('.country').attr('data-value');
        var city = $(this).parent().siblings('.city').text();
        var date_of_birth = $(this).parent().siblings('.date_of_birth').text();
        $("#uid").val(id);
        $("#name").val(name);
        $("#email").val(email);
        $("#country").val(country);
        $("#city").val(city);
        $("#date_of_birth").val(date_of_birth);

    });

    /*
	Modal Dismiss
	*/
    $(document).on('click', '#cancel', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

	/*
	Create user
	*/
    $(document).on('click', '#confirm', function (e) {
        e.preventDefault();
        if ($("#confirm").attr('data') == 'create') {
            $.ajax({
                url: URL + '/admlogin/index/create',
                data: $("#formData").serialize(),
                type: "POST",
                dataType: "json",
                success: function (response) {

                    if (response.success == true) {
                        notify('Success', 'User added', 'success');
                        // var item = response.rowData;
                        // $('tbody').append('<tr>' +
                        //     '<td>' + item.name + '</td>' +
                        //     '<td>' + item.email + '</td>' +
                        //     '<td>' + item.city + '</td>' +
                        //     '<td>' + item.country + '</td>' +
                        //     '<td>' + item.date_of_birth + '</td>' +
                        //     '<td>' + item.created_at + '</td>' +
                        //     '<td class="actionsCell"><a href="#modalEdit" class="modalEdit"><i aria-hidden="true" class="fa fa-pencil primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modalDelete" class="modalDelete"><i aria-hidden="true" class="fa fa-trash text-danger"></i></a></td>' + '</tr>');
                        $.magnificPopup.close();

                        setTimeout(function () {
                            paginate_handling(response.paginatorData);
                        }, 1000);

                    }
                },
                error: function (res) {
                    var errors = res.responseJSON.errors;
                    $('.errors').remove();
                    $('.input_error').removeClass('input_error');
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            $('#' + key).addClass('input_error');
                            $('#' + key).after('<p class="text-danger errors">' + errors[key] + '</p>');
                        }
                    }
                }

            });
        } else {
            /*
            Edit user
            */
            $.ajax({
                url: URL + '/admlogin/index/edit',
                data: $("#formData").serialize(),
                type: "PUT",
                dataType: "json",
                success: function (response) {

                    if (response.success == true) {
                        notify('Success', 'User edited', 'success');

                        $.magnificPopup.close();

                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    }
                },
                error: function (res) {
                    var errors = res.responseJSON.errors;
                    $('.errors').remove();
                    $('.input_error').removeClass('input_error');
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            $('#' + key).addClass('input_error');
                            $('#' + key).after('<p class="text-danger errors">' + errors[key] + '</p>');
                        }
                    }
                }

            });

        }

    });

    /*
    Delete user
    */
    $(document).on('click', '#confirmDelete', function (e) {
        e.preventDefault();

        $.ajax({
            url: URL + '/admlogin/index/delete',
            data: { formData: $("#deleteUser").serialize(), currentUrl: window.location.href },
            type: "DELETE",
            dataType: "json",
            success: function (response) {

                if (response.success == true) {
                    notify('Success', 'User deleted', 'success');

                    $.magnificPopup.close();

                    setTimeout(function () {
                        paginate_handling(response.paginatorData, response.currentUrl);
                    }, 1000);

                }
            },
            error: function (res) {
                var errors = res.responseJSON.errors;
                $('.errors').remove();
                $('.input_error').removeClass('input_error');
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        $('#' + key).addClass('input_error');
                        $('#' + key).after('<p class="text-danger errors">' + errors[key] + '</p>');
                    }
                }
            }

        });
    });



    // datepicker close and change format
    $('#date_of_birth').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });
}).apply(this, [jQuery]);