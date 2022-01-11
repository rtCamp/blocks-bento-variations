/**
 * Block's Front-end script.
 */

/**
 * Import FE styles for building.
 */
import './style.scss';

(async () => {
	await window.customElements.whenDefined('bento-base-carousel');
	await window.customElements.whenDefined('bento-lightbox');

	const bentoComponents = document.querySelectorAll(
		'.web-stories-list__carousel--bento'
	);

	/**
	 * Adding bento-lightbox & amp-story-player events.
	 *
	 * @return {void}
	 */
	const addEventListeners = async () => {
		const bentoStories = document.querySelectorAll(
			'.web-stories-list .web-stories-list__inner-wrapper'
		);

		for (const bentoStory of bentoStories) {
			const bentoLightbox = bentoStory.querySelector('bento-lightbox');

			if (bentoLightbox) {
				const lightboxAPI = await bentoLightbox.getApi();

				const ampStoryPlayer =
					bentoStory.querySelector('amp-story-player');

				// Event triggered when user clicks on close (X) button.
				ampStoryPlayer.addEventListener(
					'amp-story-player-close',
					() => {
						// Rewind the story and pause there upon closing the lightbox.
						ampStoryPlayer.rewind();
						ampStoryPlayer.pause();
						ampStoryPlayer.mute();
						lightboxAPI.close();

						document.body.classList.toggle(
							'web-stories-lightbox-open'
						);
					}
				);

				const storyLinks = ampStoryPlayer
					.getStories()
					.map(({ href }) => href);

				bentoStory
					.querySelectorAll(
						'bento-base-carousel .web-stories-list__story a'
					)
					.forEach((story, index) => {
						story.addEventListener('click', () => {
							lightboxAPI.open();
							ampStoryPlayer.show(storyLinks[index]);
							ampStoryPlayer.play(storyLinks[index]);

							document.body.classList.toggle(
								'web-stories-lightbox-open'
							);
						});
					});
			}
		}
	};

	/**
	 * Adjust styles of current bento-base-carousel.
	 *
	 * @param {Node} bentoComponent DOM node.
	 * @return {void}
	 */
	const setBentoCarouselStyles = (bentoComponent) => {
		const stories = bentoComponent.querySelectorAll(
			'.web-stories-list__story'
		);
		const storyItem = bentoComponent.querySelector(
			'.web-stories-list__story'
		);

		const itemStyle = window.getComputedStyle(storyItem);
		const itemWidth =
			parseFloat(itemStyle.width) +
			(parseFloat(itemStyle.marginLeft) +
				parseFloat(itemStyle.marginRight));

		// Set bento-carousel height equal to Story height
		bentoComponent.style.height = itemStyle.height;

		// Force correct trackWidth.
		const trackWidth = itemWidth * stories.length;
		bentoComponent.style.width = `${trackWidth}px`;
	};

	for (const bentoComponent of bentoComponents) {
		const carouselWrapper = bentoComponent.parentElement;
		const api = await bentoComponent.getApi();

		// Add custom events for bento-lighbox & amp-story-player component.
		addEventListeners();

		// Add styles to bento-base-carousel component.
		setBentoCarouselStyles(bentoComponent);

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

		// Remove default web-stories-lightbox of current block.
		const storyId = bentoComponent.closest('.web-stories-list').dataset.id;

		const webStoryLightbox = document.querySelector(
			`.web-stories-list__lightbox-wrapper.ws-lightbox-${storyId}`
		);

		if (webStoryLightbox) {
			webStoryLightbox.remove();
		}
	}
})();
