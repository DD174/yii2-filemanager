$(document).ready(function() {
    var ajaxRequest = null,
        fileInfoContainer = $("#fileinfo"),
        strictThumb = $(window.frameElement).parents('[role="filemanager-modal"]').attr("data-thumb"),
        menuFolder = $(".filemanager__menu-folder"),
        htmlLoad = '<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>';

    function setAjaxLoader() {
        $("#fileinfo").html(htmlLoad);
    }

    $('[href="#mediafile"]').on("click", function(e) {
        e.preventDefault();

        if (ajaxRequest) {
            ajaxRequest.abort();
            ajaxRequest = null;
        }

        $(".item a").removeClass("active");
        $(this).addClass("active");
        var id = $(this).attr("data-key"),
            url = $("#filemanager").attr("data-url-info");

        ajaxRequest = $.ajax({
            type: "GET",
            url: url,
            data: "id=" + id + "&strictThumb=" + strictThumb,
            beforeSend: function() {
                setAjaxLoader();
            },
            success: function(html) {
                $("#fileinfo").html(html);
            }
        });
    });

    fileInfoContainer.on("click", '[role="delete"]', function(e) {
        e.preventDefault();

        var url = $(this).attr("href"),
            id = $(this).attr("data-id"),
            confirmMessage = $(this).attr("data-message");

        $.ajax({
            type: "POST",
            url: url,
            data: "id=" + id,
            beforeSend: function() {
                if (!confirm(confirmMessage)) {
                    return false;
                }
                $("#fileinfo").html(htmlLoad);
            },
            success: function(json) {
                if (json.success) {
                    $("#fileinfo").html('');
                    $('[data-key="' + id + '"]').fadeOut();
                }
            }
        });
    });

    fileInfoContainer.on("submit", "#control-form", function(e) {
        e.preventDefault();

        var url = $(this).attr("action"),
            data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            beforeSend: function() {
                setAjaxLoader();
            },
            success: function(html) {
                $("#fileinfo").html(html);
            }
        });
    });

    fileInfoContainer.on('click', '.add-folder', function(e) {
        e.preventDefault();

        $("#filemanager-folder-name").toggleClass('hidden', false);
    });

    menuFolder.on("click", '[role="folder-delete"]', function(e) {
        e.preventDefault();

        var url = menuFolder.data("href"),
            id = $(this).data("id"),
            confirmMessage = menuFolder.data("message"),
            backupHtml = menuFolder.html();

        $.ajax({
            type: "POST",
            url: url,
            data: {id: id},
            beforeSend: function() {
                if (!confirm(confirmMessage)) {
                    return false;
                }
                menuFolder.html(htmlLoad);
            },
            success: function(json) {
                if (json.success) {
                    menuFolder.html('');
                    window.location.reload();
                } else {
                    menuFolder.html(backupHtml);
                    window.alert(json.message);
                }
            }
        });
    });

    menuFolder.on("click", '[role="folder-edit"]', function(e) {
        e.preventDefault();

        var url = $(this).attr("href");

        $.ajax({
            type: "GET",
            url: url,
            beforeSend: function() {
                setAjaxLoader();
            },
            success: function(html) {
                $("#fileinfo").html(html);

                return false;
            }
        });

        return false;
    });
});
