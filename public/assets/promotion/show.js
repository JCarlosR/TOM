var $titleFanPage;
var $alertMessage, $pBackLink;
var $alertLike;
var $btnGo;
var $pInstructions, $pCount, $pLogout;
var $promoData, $participantData;

$(function () {
    $titleFanPage = $('#titleFanPage');
    $btnGo = $('#btnGo');
    $pCount = $('#pCount');
    $alertLike = $('#alertLike');
    $pBackLink = $('#pBackLink');
    $alertMessage = $('#alertMessage');
    $pInstructions = $('#pInstructions');
    $pLogout = $('#pLogout');

    $promoData = $('#promoData');
    $participantData = $('#participantData');

    $btnGo.on('click', onClickButtonGo);
    $('#closeAlertLike').on('click', onClickCloseAlertLike);
});

function onClickCloseAlertLike() {
    event.preventDefault();
    $alertLike.slideUp('slow'); // hide with animation
}
function showAlertLike() {
    $alertLike.slideDown('slow'); // show with animation
}

function onClickButtonGo() {
    if (requestCheckIn) {
        // alert('Por favor comparte antes de continuar :)');
        startCheckIn();
        return;
    }

    var url = $(this).data('action');
    var csrfToken = $(this).data('token');
    var data = {
        _token: csrfToken
    };

    $btnGo.prop('disabled', true);
    $.ajax({
        method: 'POST',
        url: url,
        data: data
    }).done(function(response) {
        $btnGo.prop('disabled', false);
        showGoResponse(response);
    });
}

function showGoResponse(data) {
    if (data.success) {
        // The participation was registered
        if (data.participation.is_winner) {
            displayIsWinnerMessage(data.participation.id);
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
            case 'must_wait':
                errorMessage = 'Ya has participado en esta promo. Puedes volver a participar luego de 24 horas!';
                $btnGo.remove();
                break;
            case 'invalid_promotion':
                errorMessage = 'Ups algo falló en tu TomboFan, contacta de inmediato a '+data.name+' al correo '+data.email;
                break;
            default:
                errorMessage = 'Ha ocurrido un error inesperado. Por favor inténtalo en unos minutos.';
                break;
        }
        displayErrorAlert(errorMessage);
    }
}

function displayIsWinnerMessage(participationId) {
    displaySuccessfulAlert('Folio de Promoción Ganador ('+participationId+')');
    // show winner image
    $('#imgWon').fadeIn('slow');
    $btnShare.show();
    participationPerformed();
}
function displayNonWinnerMessage() {
    // displayWarningAlert('Vuelve a intentarlo el día de mañana!');
    // show non-winner image
    $('#imgLost').fadeIn('slow');
    participationPerformed();
}
function participationPerformed() {
    $('#promoData, #participantData').slideUp('slow', function () {
        $(this).remove();
    });
    $pCount.hide();
    $btnGo.hide();
    $pInstructions.hide();
    $pBackLink.show();
    $pLogout.hide();
}

function displaySuccessfulAlert(message) {
    displayAlert('success', message);
}
function displayWarningAlert(message) {
    displayAlert('warning', message);
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