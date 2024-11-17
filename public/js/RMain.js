// $(document.body).on("click", "#chat-root .submit-new-ticket", function() {})
// <meta name="csrf-token" content="{{ csrf_token() }}" />
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
function viewAjax(url, data, place, success = null, error = null) {
    $("#loading").fadeIn();
    $.get(url, data)
        .done(function (response) {
            $(place).html(response);
            $("#loading").fadeOut();
            if (success != null && typeof success === "function") {
                success(response);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            $("#loading").fadeOut();
            if (error == null) {
                alert("error");
                console.error("AJAX request failed:", textStatus, errorThrown);
            } else {
                error(response);
            }
        });
}

function actionAjax(url, data, title, text=null, success = null, error = null) {
    $("#loading-overlay").fadeIn();
    // data._token=$('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "get",
        url: url,
        data: data,
        success: function (response) {
            $("#loading-overlay").fadeOut();
            if (title) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: "success",
                    confirmButtonText: 'باشه'
                });
            }

            if (success != null && typeof success === "function") {
                success(response);
            }
        },
        error: function (response) {
            $("#loading-overlay").fadeOut();
            console.log(response);
            if (error == null) {
                Swal.fire({
                    title: "خطا",
                    text: response.responseJSON.message,
                    icon: "error",
                });
            } else {
                error(response);
            }
        },
    });
}

function formDataAjax(url, data, title, text, success = null, error = null) {
    $("#loading").fadeIn();
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#loading").fadeOut();
            if (title) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: "success",
                    confirmButtonText: 'باشه'
                });
            }
            if (success != null && typeof success === "function") {
                success(response);
            }
        },
        error: function (response) {
            $("#loading").fadeOut();
            if (error == null) {
                Swal.fire({
                    text: response.responseJSON.message,
                    icon: "error",
                });
            } else {
                error(response);
            }
        },
    });
}
