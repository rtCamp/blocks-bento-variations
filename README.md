# Bento Variations of Gutenberg Blocks

[![Project Status: Active – The project has reached a stable, usable state and is being actively developed.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)

Adds new block variations of existing blocks, which will utilize the Bento components. This will allow us to better compare Bento components based Gutenberg blocks and non-Bento Gutenberg blocks.

## Plugin Structure

```markdown
.
├── assets
│   ├── build
│   │   ├── js
│   │   │   ├── editor.js
│   │   │   ├── editor.asset.php
│   │   ├── css
│   │   │   ├── editor.css
│   ├── src
│   │   ├── js
│   │   │   ├── editor.js
│   │   ├── scss
│   │   │   ├── editor.css
│   ├── .babelrc
│   ├── .eslintignore
│   ├── .eslintrc.json
│   ├── package.json
│   ├── package-lock.json
│   ├── webpack.config.js
├── inc
│   ├── classes
│   │   ├── class-assets.php
│   │   ├── class-plugin.php
│   │   └── blocks
│   ├── helpers
│   │   ├── autoloader.php
│   └── traits
│       └── trait-singleton.php
└── blocks-bento-variations.php
```

## Blocks and their variations
TBD

## Assets

Assets folder contains webpack setup and can be used for creating blocks or adding any other custom scripts like javascript for admin.

- Run `npm i` from `assets` folder to install required npm packages.
- Use `npm run dev` during development for assets.
- Use `npm run prod` for production.

### Reporting a bug 🐞

Before creating a new issue, do browse through the [existing issues](https://github.com/rtCamp/features-plugin-skeleton/issues) for resolution or upcoming fixes. 

If you still need to [log an issue](https://github.com/rtCamp/blocks-bento-variations/issues/new), making sure to include as much detail as you can, including clear steps to reproduce your issue if possible.
