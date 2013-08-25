function adjustBorderColour() {
	var elems = document.getElementsByClassName('halves--half');

	for ( var i = elems.length - 1; i >= 0; i = i - 1 ) {
		elems[i].style.borderColor = 'rgba(' + elems[i].getAttribute( 'data-brush-colour' ) + ', .3)';
	}
}

adjustBorderColour();