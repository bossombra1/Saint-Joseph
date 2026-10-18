import frenchTranslations from '../../lang/ui.fr.json';

const translatableAttributes = ['aria-label', 'alt', 'placeholder', 'title'];
const ignoredElements = new Set(['CODE', 'PRE', 'SCRIPT', 'STYLE', 'TEXTAREA']);

function translateTextNode(node) {
	const value = node.nodeValue ?? '';
	const trimmed = value.trim();
	const lookup = trimmed.replace(/\\s+/g, ' ');

	if (!trimmed || !Object.hasOwn(frenchTranslations, lookup)) return;

	const translated = frenchTranslations[lookup];
	if (translated === trimmed) return;

	const leadingWhitespace = value.match(/^\s*/)?.[0] ?? '';
	const trailingWhitespace = value.match(/\s*$/)?.[0] ?? '';
	node.nodeValue = `${leadingWhitespace}${translated}${trailingWhitespace}`;
}

function translateElement(element) {
	if (ignoredElements.has(element.tagName) || element.isContentEditable) return;

	for (const attribute of translatableAttributes) {
		const value = element.getAttribute(attribute);
		if (value && Object.hasOwn(frenchTranslations, value) && frenchTranslations[value] !== value) {
			element.setAttribute(attribute, frenchTranslations[value]);
		}
	}

	const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
	while (walker.nextNode()) {
		const textNode = walker.currentNode;
		if (!ignoredElements.has(textNode.parentElement?.tagName)) translateTextNode(textNode);
	}
}

if (document.documentElement.lang.startsWith('fr')) {
	const observer = new MutationObserver((mutations) => {
		for (const mutation of mutations) {
			if (mutation.type === 'characterData') {
				translateTextNode(mutation.target);
			} else if (mutation.type === 'attributes') {
				translateElement(mutation.target);
			} else {
				for (const node of mutation.addedNodes) {
					if (node.nodeType === Node.TEXT_NODE) {
						if (!ignoredElements.has(node.parentElement?.tagName)) translateTextNode(node);
					} else if (node.nodeType === Node.ELEMENT_NODE) {
						translateElement(node);
					}
				}
			}
		}
	});

	observer.observe(document.documentElement, {
		subtree: true,
		childList: true,
		characterData: true,
		attributes: true,
		attributeFilter: translatableAttributes,
	});
}

window.setLocale = (locale) => {
	if (['fr', 'en'].includes(locale)) window.location.assign(`/locale/${locale}`);
};
