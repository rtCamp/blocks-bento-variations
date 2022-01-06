/**
 * Block's Front-end script.
 */

/**
 * Import FE styles for building.
 */
import './style.scss';

(async () => {
	await window.customElements.whenDefined('bento-base-carousel');

	const bentoComponents = document.querySelectorAll(
		'.web-stories-list__carousel--bento'
	);

	for (const bentoComponent of bentoComponents) {
		const carouselWrapper = bentoComponent.parentElement;
		const api = await bentoComponent.getApi();

		carouselWrapper
			.querySelector('.bento-prev')
			.addEventListener('click', () => {
				api.prev();
			});

		carouselWrapper
			.querySelector('.bento-next')
			.addEventListener('click', () => {
				api.next();
			});
	}
})();
