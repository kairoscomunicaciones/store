# PrestaShop Design (psxdesign)

## About
### Rework of the pages in the Design entry of the Back Office
The module aims to simplify for the merchants the customization of his store. By reworking the existing pages, we want to create a better user experience.
### New features
The module will also provide new features that are missing from PrestaShop. *(e.g.: creating a logo from a text input)*

## Download & Installation
*To do.*

## Building
This part covers the steps to get this project ready locally.

In order to run on a PrestaShop instance, dependencies needs to be downloaded and the JS application built.

### PHP
Retrieve dependencies with composer

```bash
composer install
```

### VueJS
The following commands need to be run in the _dev/ folder.

To build the application in production mode:

```bash
yarn install
yarn build
```

To compiles and watch for new changes (development mode):

```bash
npm install
npm run dev
```

### How to localy test the module for the first time ?
You need to run an instance of PrestaShop with the module, you can use docker-compose commands:

```bash
make setup
make install
docker-compose start
```

* Open a new tab in your browser at `http://localhost:8686`


To access to the backoffice:

* Open a new tab in your browser at `http://localhost:8686/admin-dev`
* Email: `demo@prestashop.com`
* Password: `prestashop_demo`

Email and password can be overridden by `ADMIN_MAIL` and `ADMIN_PASSWD` env var.

In the left menu, click on `Modules` > `Module Manager` and install `psxdesign` module

## Commands

### global
* `make`: Calling help by default
* `make help`: Get help on this file

### Installation and update
* `make setup`: Setup docker-compose environment
* `make build-image`: (Re)Build docker images
* `make install`: Install dependencies
* `update-php-dep`: Update PHP dependencies

### Deploy
* `make zip`: Make a zip bundle

### Qualimetry
* `make lint-back`: Launch php linter
* `make lint-back-fix`: Launch php linter and fix files

### Tests
* `make test`: Launch all tests
* `make test-back`: Launch the tests back

### Others
* `make clean`: Clean up the repository

## Releasing
### Local generation of a .zip
To generate a zip of the module locally, you can run the command:
```bash
make zip
```
This will:
- Install vendors
- Create a `dist` folder
- Zip all the module inside the `dist` directory, with the exception of all files and folders listed in `module-files.exclude`

## Documentation
*To do.*

## Contributing
PrestaShop modules are open source extensions to the PrestaShop e-commerce platform. Everyone is welcome and even encouraged to contribute with their own improvements!

Just make sure to follow our contribution guidelines.

### Forking
>A fork is a copy of a repository. Forking a repository allows you to freely experiment with changes without affecting the original project.

To contribute, we'll ask you to create a *fork* and submit your pull request from your fork.  
- Fork the repository.
- Clone your forked repository
- Make the changes you want to make.
- Push to your forked repository
- Submit a pull request from your fork to the original repository.

If you need more explanation, you can find [the docs on forking a repository on GitHub](https://docs.github.com/en/get-started/quickstart/fork-a-repo#forking-a-repository).

### Commits
To ensure that commit message are consistent, we lint commit messages by using: `commitlint`.

To contribute, please first install `commitlint` and `husky`:

```bash
yarn install
yarn prepare
```

## Reporting issues
*To do.*

## Licence
This module is released under the Academic Free License 3.0
