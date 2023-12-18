// inspired by https://jross.me/asynchronous-and-progressive-css-loading/
const decodeHTML = function (html) {
	const textarea = document.createElement('textarea');
	textarea.innerHTML = html;
	return textarea.value;
};
const getItemsFromContainerText = function(container, selector){
	const parser = new DOMParser();
	const parsedHtml = parser.parseFromString(decodeHTML(container.textContent), 'text/html');

	return parsedHtml.querySelectorAll(selector);
};
function swapMedia() {
	this.media = this.getAttribute('data-cmpreload-media') || 'all';
}
function loadCss() {
	const preloadingCss = document.querySelectorAll('link[rel="preload"][as="style"]');
	if (!preloadingCss) {
		return;
	}

	const styleSheets = document.createDocumentFragment();

	for (const css of preloadingCss) {
		const id = css.id.replace(/-css$/, '');
		const noscript = document.querySelector(`noscript#${id}-noscript`);
		if (!noscript) continue;

		const sheets = getItemsFromContainerText(noscript, 'link[rel="stylesheet"]');
		const appendSheets = [];
		for (const sheet of sheets) {
			sheet.setAttribute('data-cmpreload-media', sheet.getAttribute('media') || 'all');
			sheet.setAttribute('media', 'print');
			sheet.setAttribute('data-cmpreloaded', 'true');
			sheet.addEventListener('load', swapMedia, { once: true });
			appendSheets.push(sheet);
		}
		styleSheets.append(...appendSheets);
		css.remove();
	}
	document.head.append(styleSheets);
}
document.addEventListener('DOMContentLoaded', loadCss);
window.addEventListener('load', loadCss);
loadCss();

/*
function swapMedia() {
	this.media = 'all'
}
function swapStyles() {
	var styles = document.querySelectorAll('link[media="print"][data-cmpreloading]');
	[].forEach.call(styles, function (s) {
		s.removeEventListener('load', swapMedia);
		if (s.sheet) {
			s.media='all';
		} else {
			s.addEventListener('load', swapMedia, { once: true } );
		}
	});
}

document.addEventListener('DOMContentLoaded', swapStyles);
window.addEventListener('load', swapStyles);

swapStyles();
*/