(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

var $btnShare;
var requestCheckIn, $btnCheckIn;
var $imgPromo;
var $pDescription;
var locationId;
$(function () {
    $imgPromo = $('#imgPromo');
    $pDescription = $('#pDescription');
    $btnShare = $('#btnShare');
    $btnCheckIn = $('#btnCheckIn');

    $btnShare.on('click', onClickButtonShare);

    // Check-in will be requested to participate
    requestCheckIn = true;
    // Check-in location
    locationId = $('#btnGo').data('location');
});

function onClickButtonShare() {
    // var promoId = $(this).data('id');
    FB.ui(
        {
            method: 'feed',
            name: 'Wow, miren lo que gane en esta TomboFans!',
            caption: '#GanadorTomboFans',
            description: $pDescription.text(),
            link: location.href,
            picture: $imgPromo.attr('src')
        },

        function(response)
        {
            if (response && response.post_id)
                alert('Gracias por compartirlo!');
            else
                alert('El post no fue publicado');
        }
    );
}

function startCheckIn() {
    FB.login(checkInApiRequest, {scope: 'publish_actions'});
}

function checkInApiRequest() {
    // var promoLink = $pBackLink.find('a').attr('href');
    var fanPageId = $btnGo.data('location');

    var data = {
        place: fanPageId
    };
    
    FB.api('/me/feed', 'post', data, function(response) {
        if (!response || response.error) {
            alert('Ha ocurrido un error inesperado!');
            console.error(response.error);
        } else {
            // alert('Gracias por compartir tu participación!');
            requestCheckIn = false;
            $btnGo.click();
        }
    });
    
    /*
    FB.ui({
        method: 'share',
        // display: 'popup',
        href: promoLink,
    }, function(response) {
        if (!response || response.error) {
            alert('Ha ocurrido un error inesperado!');
            console.error(response.error);
        } else {
            requestCheckIn = false;
            $btnGo.click();
        }
    });
    */
}