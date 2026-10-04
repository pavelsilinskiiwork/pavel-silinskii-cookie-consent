(function () {
	'use strict';

	function psccSetCookie(name, value, days) {
		document.cookie = name + '=' + value
			+ ';max-age=' + (days * 86400)
			+ ';path=/;SameSite=Lax';
	}

	function psccGetCookie(name) {
		var parts = document.cookie ? document.cookie.split('; ') : [];
		for (var i = 0; i < parts.length; i++) {
			var eq = parts[i].indexOf('=');
			if (parts[i].substring(0, eq) === name) {
				return decodeURIComponent(parts[i].substring(eq + 1));
			}
		}
		return null;
	}

	function psccHideBanner() {
		var banner = document.getElementById('pscc-banner');
		if (banner) {
			banner.classList.add('pscc-hidden');
		}
	}

	async function psccSendConsent(consent) {
		try {
			var body = new FormData();
			body.append('action', 'pscc_consent');
			body.append('nonce', psccData.nonce);
			body.append('consent', consent);
			await fetch(psccData.ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' });
		} catch (e) {
			// The cookie is already set; a failed acknowledgement is not fatal.
		}
	}

	function psccHandleConsent(consent) {
		psccSetCookie(psccData.cookieName, consent, psccData.cookieDuration);
		psccHideBanner();
		psccSendConsent(consent);
	}

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof psccData === 'undefined') {
			return;
		}

		// Double guard: a cached page may still contain the banner markup.
		if (psccGetCookie(psccData.cookieName) !== null) {
			return;
		}

		var banner = document.getElementById('pscc-banner');
		if (!banner) {
			return;
		}

		banner.classList.remove('pscc-hidden');

		var accept = banner.querySelector('.pscc-accept');
		var decline = banner.querySelector('.pscc-decline');
		var overlay = banner.querySelector('.pscc-overlay');

		if (accept) {
			accept.addEventListener('click', function () {
				psccHandleConsent('accepted');
			});
		}

		if (decline) {
			decline.addEventListener('click', function () {
				psccHandleConsent('declined');
			});
		}

		if (overlay) {
			overlay.addEventListener('click', function () {
				psccHandleConsent('declined');
			});
		}
	});
})();
