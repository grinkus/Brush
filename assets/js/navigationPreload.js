(function () {
    'use strict';

    var cache = {},
        navLinks = [
            document.getElementById('navigation-backward'),
            document.getElementById('navigation-forward')
        ],

        cacheLinks = function (html, url) {
            var links = {
                    backward: /<[^>]*id="navigation-backward"[^>]*>/g,
                    forward: /<[^>]*id="navigation-forward"[^>]*>/g
                },
                key;

            cache[url].nav = {};

            function getLink(key) {
                var elem = html.match(links[key]),
                    re = /href="([^"]*)"/g,
                    href = re.exec(elem[0]);

                if (href) {
                    return href[1];
                }

                return undefined;
            }

            for (key in links) {
                if (links.hasOwnProperty(key)) {
                    cache[url].nav[key] = getLink(key);
                }
            }
        },

        cacheHalves = function (html, url) {
            // This obviously breaks if there is at least one article in the .halves--half
            var re = /<article[^>]*halves--half[^>]*>(.*?)<\/article>/g;

            cache[url].halves = html.match(re);
        },

        cacheHtml = function (html, url) {
            cache[url] = {};
            cacheLinks(html, url);
            cacheHalves(html, url);
        },

        preloadHalves = function (url) {
            var i;

            if (!cache[url].el) {
                cache[url].el = document.createElement('div');
                cache[url].el.setAttribute('class', 'halves');

                for (i = cache[url].halves.length - 1; i >= 0; i = i - 1) {
                    cache[url].el.innerHTML = cache[url].halves[i] + cache[url].el.innerHTML;
                }
            }
        },

        insertAfter = function (newNode, referenceNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        },

        prepare = function (url) {
            var href,
                direction,
                el,
                i;

            for (i = navLinks.length - 1; i >= 0; i = i - 1) {
                href = navLinks[i].getAttribute('href');
                direction = navLinks[i].getAttribute('data-brush-direction');
                if (href && href === url) {
                    navLinks[i].setAttribute('class', navLinks[i].getAttribute('class').replace(' not-loaded', ''));

                    preloadHalves(url);

                    el = cache[url].el;

                    if (!document.contains(el)) {
                        if (direction && direction === "backward") {
                            document.body.insertBefore(el, document.getElementById('current-page'));
                            el.setAttribute('class', 'halves halves__previous');
                        } else {
                            insertAfter(el, document.getElementById('current-page'));
                            el.setAttribute('class', 'halves halves__next');
                        }
                    }
                }
            }
        },

        getPage = function (url) {
            var xhr;

            function handleResponse() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    cacheHtml(xhr.responseText, url);
                    prepare(url);
                }
            }

            xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = handleResponse;
            xhr.send();
        },

        preloadPages = function () {
            var href,
                i;

            for (i = navLinks.length - 1; i >= 0; i = i - 1) {
                href = navLinks[i].getAttribute('href');

                if (href) {
                    if (!cache[href]) {
                        navLinks[i].setAttribute('class', navLinks[i].getAttribute('class') + ' not-loaded');
                        getPage(href);
                    } else {
                        prepare(href);
                    }
                }
            }
        },

        handleClick = function (el) {
            var url = el.getAttribute('href'),
                direction = el.getAttribute('data-brush-direction'),
                current,
                removed;

            if (url && !~el.getAttribute('class').indexOf('not-loaded')) {
                current = cache[url].el;

                document.getElementById('current-page').removeAttribute('id');
                current.setAttribute('id', 'current-page');
                current.setAttribute('class', current.getAttribute('class').replace('halves__previous', '').replace('halves__next', ''));

                switch (direction) {
                case 'forward':
                    removed = cache[navLinks[0].getAttribute('href')].el;
                    el.setAttribute('href', cache[url].nav.forward);
                    navLinks[0].setAttribute('href', cache[url].nav.backward);
                    break;
                case 'backward':
                    removed = cache[navLinks[1].getAttribute('href')].el;
                    el.setAttribute('href', cache[url].nav.backward);
                    navLinks[1].setAttribute('href', cache[url].nav.forward);
                    break;
                default:
                    return;
                }

                removed.parentNode.removeChild(removed);

                preloadPages();
                fitImages();
                adjustBorderColour();
            }
        },

        init = function () {
            var halvesDiv = document.getElementsByClassName('halves');

            halvesDiv[0].setAttribute('id', 'current-page');

            cacheHtml(document.documentElement.innerHTML, window.location.href);

            preloadPages();

            navLinks[0].onclick = function (e) {
                handleClick(this);
                e.preventDefault();
            };

            navLinks[1].onclick = function (e) {
                handleClick(this);
                e.preventDefault();
            };

            function clickBackwardLink() {
                navLinks[0].click();
            }

            function clickForwardLink() {
                navLinks[1].click();
            }

            document.onkeydown = function (e) {
                e = e || window.event;
                switch (e.keyCode) {
                case 37: // Cursor left key
                    clickBackwardLink();
                    break;
                case 39: // Cursor right key
                    clickForwardLink();
                    break;
                case 74: // 'j' key
                    clickBackwardLink();
                    break;
                case 75: // 'k' key
                    clickForwardLink();
                    break;
                default:
                    return;
                }
            };
        };

    init();
}());