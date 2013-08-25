function fitImages () {
	'use strict';

	var images = document.getElementsByClassName('image portrait'),
		viewportHeight = document.documentElement.clientHeight;

	for (var i = images.length - 1; i >= 0; i -= 1) {
		images[i].style.maxHeight = 'calc(' + viewportHeight +'px - 10em)';
	}
};

fitImages();

window.onresize = function () {
    fitImages();
};