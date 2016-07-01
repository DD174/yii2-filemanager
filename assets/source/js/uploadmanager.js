$(document).ready(function() {
    var uploadManagerContainer = $("#uploadmanager");

    uploadManagerContainer.on('click', '.add-folder', function(e) {
        e.preventDefault();

        $("#filemanager-folder-name").toggleClass('hidden', false);
    });
});
