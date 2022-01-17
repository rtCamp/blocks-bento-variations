# Blocks' Bento Variations
Experimental Plugin for comparison of Bento based Gutehberg blocks with their non-Bento versions.

[![Total alerts](https://img.shields.io/lgtm/alerts/g/rtCamp/blocks-bento-variations.svg?logo=lgtm&logoWidth=18)](https://lgtm.com/projects/g/rtCamp/blocks-bento-variations/alerts/)
[![Language grade: JavaScript](https://img.shields.io/lgtm/grade/javascript/g/rtCamp/blocks-bento-variations.svg?logo=lgtm&logoWidth=18)](https://lgtm.com/projects/g/rtCamp/blocks-bento-variations/context:javascript)

## Bento Introduction

Bento AMP offers well-tested, cross-browser compatible and accessible components that can be used on non-AMP pages without having to use AMP anywhere else. Bento components are designed to be highly performant and contribute to an excellent page experience.

The plugin creates new variations of a few selected Gutenberg blocks. The new variations are created using the [Bento components](https://amp.dev/documentation/guides-and-tutorials/start/bento_guide/). This allows us to compare the Bento based Gutenberg block with their normal version.

## Technical Details 🔩
The aim is to use [the Block Variations API](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/) for creating the Bento variations of the blocks. Then, to modify the Bento variations' markup to use the Bento components on the Front-end. The use of the Block Variations API reduces efforts of re-creating the block's Editor functionalities.

## Using the Plugin

### Plugin Files Structure 📁

```markdown
.
├── README.md
├── assets
│   ├── build
│   │   ├── css
│   │   └── js
│   ├── package-lock.json
│   ├── package.json
│   ├── postcss.config.js
│   ├── src
│   │   ├── js
│   │   └── scss
│   └── webpack.config.js
├── inc
│   ├── classes
│   ├── helpers
│   └── traits
├── blocks-bento-variations.php
└── phpcs.xml
```
### Building Assets
Assets folder contains webpack setup and can be used for creating blocks or adding any other custom scripts.

- Run `npm i` from `assets` folder to install required npm packages.
- Use `npm run dev` during development for assets.
- Use `npm run prod` for production build.
- Use `npm run lint-js` for linting JavaScript.
- Use `npm run lint-css` for linting CSS.


## Blocks and Their Variations

| Block                                     | Variation          | Is AMP Compatible?  | Dependency             |
|-------------------------------------------|--------------------|--------------------|--------------------|
| [Slideshow](https://github.com/Automattic/jetpack/tree/master/projects/plugins/jetpack/extensions/blocks/slideshow) | Slideshow (Bento) | Yes | [Jetpack Plugin](https://wordpress.org/plugins/jetpack/)
| [Accordion](https://github.com/godaddy-wordpress/coblocks/tree/master/src/blocks/accordion) | Accordion (Bento) | Yes | [CoBlocks](https://wordpress.org/plugins/coblocks/)
| [Web Stories](https://github.com/google/web-stories-wp/tree/b5dc0dcd9449947062fd43606c052af0773ef2cb/includes/Block) | Web Stories (Bento) | Yes | [Web Stories](https://wordpress.org/plugins/web-stories/)|
| [Sharing](https://atomicblocks.com/blocks/sharing-icons-block/) | Sharing (Bento) | Yes | [Atomic Blocks](https://wordpress.org/plugins/atomic-blocks/)|

## Roadmap
At present, only one block variation has been added, Slideshow. There are many more awesome [Bento Components Available](https://amp.dev/documentation/guides-and-tutorials/start/bento_guide/#available-bento-components) which will be used to create new Block Bento Variations of existing blocks.

## Reporting a Bug 🐞

Before creating a new issue, do browse through the [existing issues](https://github.com/rtCamp/blocks-bento-variations/issues/) for resolution or upcoming fixes.

If you still need to [log an issue](https://github.com/rtCamp/blocks-bento-variations/issues/new), making sure to include as much detail as you can, including clear steps to reproduce your issue if possible.

## Credits ✨

Inspiration from [gutenberg-bento](https://github.com/swissspidy/gutenberg-bento) (By [Pascal Birchler](https://github.com/swissspidy))
