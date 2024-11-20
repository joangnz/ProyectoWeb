
$('#header-main').load('../sites/templates/header-main.php');
$('#header').load('../sites/templates/header.html');

$('#footer').load('../sites/templates/footer.html');

$(document).ready(function () {
    // Delegar el evento 'click' en el contenedor del header
    $('#header-main').on('click', '#logout', function (event) {
        event.preventDefault();  // Prevenir la acción predeterminada

        // Realizar la solicitud AJAX
        $.ajax({
            url: 'main.php',  // Enviar la solicitud a 'main.php'
            type: 'POST',
            data: { action: 'logout' },  // Enviar el parámetro para identificar la acción
            success: function (response) {
                console.log("SUCCESS" + response);  // Aquí puedes manejar la respuesta de PHP
                if (response === 'logged_out') {
                    window.location.href = 'login.php';  // Redirigir al login después del logout
                }
            },
            error: function () {
                alert('Hubo un error al ejecutar la función PHP.');
            }
        });
    });
});