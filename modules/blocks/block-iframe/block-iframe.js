// document.addEventListener("DOMContentLoaded", function () {
	// window.addEventListener('message', function(event) {
	// 	var frames = document.getElementsByTagName('iframe');
	// 	for (var i = 0; i < frames.length; i++) {
	// 		if (frames[i].contentWindow === event.source) {
	// 		  var style = frames[i].currentStyle || window.getComputedStyle(frames[i]);
	// 		  var paddingTop = style.paddingTop;
	// 		  var paddingBottom = style.paddingBottom;
	// 		  var padding = parseInt(paddingTop) + parseInt(paddingBottom);
	// 		  frames[i].style.height = event.data+padding+'px';
	// 		  break;
	// 		}
	// 	}
	// });
// });


// Inside the iframe document
(function postSelfHeight() {
  function getDocHeight() {
	// Choose a robust measurement for your content
	const doc = document.documentElement;
	const body = document.body;
	return Math.max(
	  body.scrollHeight, body.offsetHeight, body.clientHeight,
	  doc.scrollHeight, doc.offsetHeight, doc.clientHeight
	);
  }

  function postHeight() {
	const height = getDocHeight();
	window.parent.postMessage({ type: 'IFRAME_HEIGHT', height }, '*');
  }

  // Post on key lifecycle moments
  document.addEventListener('DOMContentLoaded', () => {
	postHeight();
	// One more tick for fonts/images
	requestAnimationFrame(postHeight);
	setTimeout(postHeight, 200);
  });

  window.addEventListener('load', postHeight);
  window.addEventListener('resize', postHeight);

  // If content mutates later (accordions, async content), observe and post
  if ('MutationObserver' in window) {
	const mo = new MutationObserver(() => postHeight());
	mo.observe(document.documentElement, { childList: true, subtree: true, attributes: true, characterData: true });
  }
})();