$(document).ready(function() {
    $('#toggle-sidebar').on('click', function(e) {
        e.preventDefault();
        $('#sidebar').toggleClass('sidebar-collapsed');
    });
});