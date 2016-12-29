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
var $imgPromo;
var $pDescription;
$(function () {
    $imgPromo = $('#imgPromo');
    $pDescription = $('#pDescription');
    $btnShare = $('#btnShare');

    $btnShare.on('click', onClickButtonShare);
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
