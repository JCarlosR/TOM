window.fbAsyncInit = function() {
    FB.init({
        appId      : '553974898124336',
        xfbml      : true,
        version    : 'v2.8'
    });
    FB.AppEvents.logPageView();
};

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
$(function () {
    $imgPromo = $('#imgPromo');
    $pDescription = $('#pDescription');
    $btnShare = $('#btnShare');
    $btnCheckIn = $('#btnCheckIn');

    $btnShare.on('click', onClickButtonShare);

    // Check-in will be requested if the user already has liked the page
    requestCheckIn = !$alertLike.is(':visible');
});

function onClickButtonShare() {
    var promoId = $(this).data('id');
    FB.ui(
        {
            method: 'feed',
            name: 'Wow, miren lo que gane en esta TomboFans!',
            caption: '#GanadorTomboFans',
            description: $pDescription.text(),
            link: 'https://tombofans.com/facebook/promotion/'+promoId,
            picture: $imgPromo.attr('src')
        },

        function(response)
        {
            if (response && response.post_id)
            {
                alert('Gracias por compartirlo!');
            }
            else
            {
                alert('El post no fue publicado');
            }
        }
    );
}

function startCheckIn() {
    FB.login(checkInApiRequest, {scope: 'publish_actions'});
}

function checkInApiRequest() {
    var body = 'Participando en una TomboFan!';
    var data = {
        message: body,
        place: 100243956684767
    };

    FB.api('/me/feed', 'post', data, function(response) {
        if (!response || response.error) {
            alert('Ha ocurrido un error inesperado!');
        } else {
            alert('Gracias por compartir tu participación!');
            requestCheckIn = false;
            console.log(response.id);
        }
    });
}