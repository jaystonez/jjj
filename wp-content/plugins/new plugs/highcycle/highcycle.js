hs.graphicsDir = high_cycle.graphicsurl;
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.dimmingOpacity = 0.75;
hs.showCredits = false;
hs.useBox = true;
hs.width = high_cycle.width;
hs.height = high_cycle.height;
hs.addSlideshow({
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: 1,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
var $jwhc = jQuery.noConflict();
$jwhc(document).ready(function() {
    $jwhc('.jw-highcycle').cycle({
		fx: 'fade',
		speed: parseInt(high_cycle.speed),
		delay: parseInt(high_cycle.delay),
		pause: parseInt(high_cycle.pause),
		pager: '.jw-hc-pages',
		prev: '.jw-hc-prev',
		next: '.jw-hc-next'
	});
});