(function (){
    'use strict';

    var navigation = {},
        getPage = function ( url, which ) {
            var xhr = null,
                temp = null,
                handleResponse = function () {
                    if ( xhr.readyState == 4 && xhr.status == 200 ) {
                        temp = document.createElement( 'div' );
                        temp.innerHTML = xhr.responseText;

                        setupDirection(temp, which);

                        temp = null;
                    }
                };

            navigation[ which ].loaded = false;

            xhr = new XMLHttpRequest();
            xhr.open( 'GET', url, true );
            xhr.onreadystatechange = handleResponse;
            xhr.send();
        },

        setupDirection = function ( elem, which ) {
            navigation[ which ] = {};

            navigation[ which ].halves = elem.getElementsByClassName( 'halves--half' );
            navigation[ which ].navLinks = elem.getElementsByClassName( 'navigate' );

            navigation[ which ].loaded = true;

            checkNavigationLinks();
        },

        preloadPages = function () {
            var href,
                elem;

            for ( var i = navigation['current'].navLinks.length - 1; i >= 0; i = i - 1 ) {
                elem = navigation['current'].navLinks[i];
                href = elem.getAttribute('href');

                if ( href ) {
                    getPage( href, elem.getAttribute( 'data-brush-direction' ) );
                }

                href = null;
                elem = null;
            };
        },

        show = function( which ) {
            var half,
                halves = document.getElementsByClassName( 'halves--half' ),
                href;

            for ( var i = halves.length - 1; i >= 0; i = i - 1) {
                half = navigation[ which ].halves[i];
                halves[i].innerHTML = half.innerHTML;
                halves[i].setAttribute( 'data-brush-colour', half.getAttribute( 'data-brush-colour' ) );
            };

            href = navigation[ which ].navLinks[0].getAttribute( 'href' );
            if ( href ) {
                document.getElementById( 'navigation-backward' ).setAttribute( 'href', href );
            } else {
                document.getElementById( 'navigation-backward' ).removeAttribute( 'href' );
            }

            href = navigation[ which ].navLinks[1].getAttribute( 'href' );
            if ( href ) {
                document.getElementById( 'navigation-forward' ).setAttribute( 'href', href );
            } else {
                document.getElementById( 'navigation-forward' ).removeAttribute( 'href' );
            }

            resetCurrent();

            fitImages();
            adjustBorderColour();
        },

        bindClickTriggers = function () {
            navigation.elems = document.getElementsByClassName( 'navigate' );

            for (var i = navigation.elems.length - 1; i >= 0; i = i - 1) {
                var elem = navigation.elems[i];

                navigation[ elem.getAttribute( 'data-brush-direction' ) ] = elem.getAttribute( 'href' );

                elem.onclick = function (e) {
                    window.history.pushState( false, document.title, this.getAttribute( 'href' ) );
                    show( this.getAttribute( 'data-brush-direction' ) );
                    this.className = this.className + ' not-loaded';
                    e.preventDefault();
                };
            };
        },

        resetCurrent = function () {
            setupDirection( document, 'current' );

            preloadPages();
        },

        checkNavigationLinks = function () {
            var forward = document.getElementById( 'navigation-forward' ),
                backward = document.getElementById( 'navigation-backward' );

            if ( !navigation.forward.loaded ) {
                if ( !~forward.className.indexOf(' not-loaded') ) {
                    forward.className = forward.className + ' not-loaded';
                }
            } else {
                forward.className = forward.className.replace(' not-loaded', '');
            }

            if ( !navigation.backward.loaded ) {
                if ( !~backward.className.indexOf(' not-loaded') ) {
                    backward.className = backward.className + ' not-loaded';
                }
            } else {
                backward.className = backward.className.replace(' not-loaded', '');
            }
        },

        init = function () {
            bindClickTriggers();

            resetCurrent();

            // navigation.interval = setInterval( function () {
            //     checkNavigationLinks();
            // }, 500 );
        }();
})();

/*

# Pirmiausia
Davus du adresus preloadinam du page'us. navigate.backward ir navigate.forward

*/