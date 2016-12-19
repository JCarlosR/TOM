$(function () {
    $('#btnGo').on('click', onClickButtonGo);
});

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
        console.log(response);
    });
}