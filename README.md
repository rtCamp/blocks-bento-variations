# Bento Variations of Gutenberg Blocks

[![Project Status: Active – The project has reached a stable, usable state and is being actively developed.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)

Adds new block variations of existing blocks, the variations will utilize [Bento components](https://amp.dev/documentation/guides-and-tutorials/start/bento_guide/). This will allow us to better compare Bento components based Gutenberg blocks and non-Bento Gutenberg blocks.

## Plugin Structure

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

## Blocks and their variations

| Block                                     | Variation          | Is AMP Compatible?  | Plugin             |
|-------------------------------------------|--------------------|--------------------|--------------------|
| [Slideshow](https://github.com/Automattic/jetpack/tree/master/projects/plugins/jetpack/extensions/blocks/slideshow) | Slideshow (Bento) | Yes | [Jetpack](https://github.com/Automattic/jetpack)

## Assets

Assets folder contains webpack setup and can be used for creating blocks or adding any other custom scripts like javascript for admin.

- Run `npm i` from `assets` folder to install required npm packages.
- Use `npm run dev` during development for assets.
- Use `npm run prod` for production build.
- Use `npm run lint-js` for linting JavaScript.
- Use `npm run lint-css` for linting CSS.

### Reporting a bug 🐞

Before creating a new issue, do browse through the [existing issues](https://github.com/rtCamp/blocks-bento-variations/issues/) for resolution or upcoming fixes.

If you still need to [log an issue](https://github.com/rtCamp/blocks-bento-variations/issues/new), making sure to include as much detail as you can, including clear steps to reproduce your issue if possible.
