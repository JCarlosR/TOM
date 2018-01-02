var control_timeout, footerHeight;
$(document).foundation();

$(document).ready(function(){
	$("html").niceScroll({ autohidemode: false });
	/*$('#menu').localScroll({
		hash: true, 
		onAfter: function(target) {
			target.scrollTo({top:'-=25px'}, 'fast');
		}
	});*/
	$('.flexslider').flexslider({
      animation: "fade",
      directionNav: true,
      controlNav: false,
      pauseOnAction: true,
      pauseOnHover: true,
      direction: "horizontal",
      slideshowSpeed: 5500
    });	
});


function valemail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
