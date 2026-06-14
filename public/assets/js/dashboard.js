$(document).ready(function() {
    $('#toggle-sidebar').on('click', function(e) {
        e.preventDefault();
        $('#sidebar').toggleClass('sidebar-collapsed');
    });

    if ($('#stats-container').length) {
        $.ajax({
            url: '/public/controller/EstadisticasController.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#stat-examenes').text(data.examenes);
                $('#stat-coordinadores').text(data.coordinadores);
                $('#stat-carreras').text(data.carreras);
                $('#stat-salones').text(data.salones);
            }
        });
    }
});