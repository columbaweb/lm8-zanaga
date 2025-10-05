document.addEventListener("DOMContentLoaded", function () {
	window.addEventListener('message', function(event) {
		var frames = document.getElementsByTagName('iframe');
		for (var i = 0; i < frames.length; i++) {
			if (frames[i].contentWindow === event.source) {
			  var style = frames[i].currentStyle || window.getComputedStyle(frames[i]);
			  var paddingTop = style.paddingTop;
			  var paddingBottom = style.paddingBottom;
			  var padding = parseInt(paddingTop) + parseInt(paddingBottom);
			  frames[i].style.height = event.data+padding+'px';
			  break;
			}
		}
	});
});
