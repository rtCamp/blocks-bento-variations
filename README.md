# Blocks' Bento Variations
Experimental Plugin for comparison of Bento based Gutehberg blocks with their non-Bento versions.

## Bento Introduction

Bento AMP offers well-tested, cross-browser compatible and accessible components that can be used on non-AMP pages without having to use AMP anywhere else. Bento components are designed to be highly performant and contribute to an excellent page experience.

The plugin creates new variations of a few selected Gutenberg blocks. The new variations are created using the [Bento components](https://amp.dev/documentation/guides-and-tutorials/start/bento_guide/). This allows us to compare the Bento based Gutenberg block with their normal version.

## Technical Details 🔩
The aim is to use [the Block Variation API](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/) for creating a Bento variation of the blocks. Then, to modify the Bento variation's markup to use the Bento components on the Front-end. The use of the Blocks Variations API reduces efforts of re-creating the block's Editor functionalities.

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
├── package-lock.json
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

## Known Issues
Initial plans were to have Bento Components based Blocks available for both AMP & Non-AMP pages. But Bento Components are experimentally available at present and so they require [enabling of experimental features](https://amp.dev/documentation/guides-and-tutorials/learn/experimental/?format=websites). The mentioned document also mentions that "Any AMP file that includes experimental features will fail [AMP Validation](https://amp.dev/documentation/guides-and-tutorials/learn/validation-workflow/validate_amp/?format=websites). Remove these experimental components for production-ready AMP documents.

## Roadmap
At present, only one block variation has been added, Slideshow. There are many more awesome [Bento Components Available](https://amp.dev/documentation/guides-and-tutorials/start/bento_guide/#available-bento-components) which will be used to create new Block Bento Variations of existing blocks.

## Reporting a Bug 🐞

Before creating a new issue, do browse through the [existing issues](https://github.com/rtCamp/blocks-bento-variations/issues/) for resolution or upcoming fixes.

If you still need to [log an issue](https://github.com/rtCamp/blocks-bento-variations/issues/new), making sure to include as much detail as you can, including clear steps to reproduce your issue if possible.

## Credits ✨

Inspiration from [gutenberg-bento](https://github.com/swissspidy/gutenberg-bento) (By [Pascal Birchler](https://github.com/swissspidy))