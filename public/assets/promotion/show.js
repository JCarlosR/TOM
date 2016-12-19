var $alertMessage;
var $alertLike;

$(function () {
    $('#btnGo').on('click', onClickButtonGo);
    $alertMessage = $('#alertMessage');

    $('#closeAlertLike').on('click', onClickCloseAlertLike);
    $alertLike = $('#alertLike');
});
function onClickCloseAlertLike() {
    event.preventDefault();
    $alertLike.slideUp('slow'); // hide with animation
}
function showAlertLike() {
    $alertLike.slideDown('slow'); // show with animation
}

function onClickButtonGo() {
    var url = $(this).data('action');
    var csrfToken = $(this).data('token');
    var data = {
        _token: csrfToken
    };

    $.ajax({
        method: 'POST',
        url: url,
        data: data
    }).done(function(response) {
        showGoResponse(response);
    });
}

function showGoResponse(data) {
    if (data.success) {
        // The participation was registered
        if (data.participation.is_winner) {
            displayIsWinnerMessage();
        } else {
            displayNonWinnerMessage();
        }
    } else {
        // The user can't participate
        var errorMessage;
        switch (data.error_type) {
            case 'token_has_expired':
                errorMessage = 'Tu sesión ha caducado. Por favor actualiza la página e inténtalo nuevamente!';
                break;
            case 'not_liked':
                showAlertLike();
                return;
            case 'must_wait':
                errorMessage = 'Ya has participado en esta promo. Puedes volver a participar luego de 24 horas!';
                break;
            default:
                errorMessage = 'Ha ocurrido un error inesperado. Por favor inténtalo en unos minutos.';
                break;
        }
        displayErrorAlert(errorMessage);
    }
}

function displayIsWinnerMessage() {
    displaySuccessfulAlert('Felicidades has ganado!');
    // show winner image
}
function displayNonWinnerMessage() {
    displayInfoAlert('Vuelve a intentarlo el día de mañana!');
    // show non-winner image
}

function displaySuccessfulAlert(message) {
    displayAlert('success', message);
}
function displayInfoAlert(message) {
    displayAlert('info', message);
}
function displayErrorAlert(message) {
    displayAlert('danger', message);
}

function displayAlert(type, message) {
    var alertHtml = '<div class="alert alert-'+type+'">';
  	alertHtml += message;
	alertHtml += '</div>';

	$alertMessage.html(alertHtml);
}