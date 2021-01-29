Notarizer [![Build Status](https://travis-ci.com/notarizer/notarizer.svg?token=qHEPSLtbmsxngUhJy7x8&branch=master)](https://travis-ci.com/notarizer/notarizer)
======

**Notarizer** is an easy and secure virtual timestamping service intended to verify that a file existed at a certain time.

This project does **NOT** use a Blockchain. 

See the **[Live Project](https://notarizer.app)**.

## Installation

This projects makes use of [Laravel Valet](https://laravel.com/docs/valet).

1. Clone the repo:
    ```
    git clone https://github.com/notarizer/notarizer.git

    cd notarizer
    ```
2. Create an environment file:
    ```
    cp .env.example .env # You must update some values in .env
    ```
3. Install composer dependencies:
    ```
    composer self-update && composer install
    ```
4. Generate PHP artisan keys:
    ```
    php artisan key:generate
    ```
5. Install NPM dependencies:
    ```
    npm i
    ```
6. Run [Laravel Mix](https://laravel.com/docs/mix)
    ```
    npm run dev # or npm run prod
    ```
7. Test it out! This varies based on how you configured your environment.

## Testing

1. Make sure you have your environment set up.
2. Run the test suite:
    ```
    composer test
    ```

## Contributors

### Contributors on GitHub
* [Contributors](https://github.com/notarizer/notarizer/graphs/contributors)

### Third party libraries
* See [package.json](package.json)
* See [composer.json](composer.json)

## License 
* See [LICENSE](LICENSE)

## Contact

#### Steven Kemp (@rockhopper72)
* Homepage: [Kemp.app](https://kemp.app/contact)
* Email: [steven@notarizer.app](mailto:steven@notarizer.app)
* Other: [notarizer.app/contact](https://notarizer.app/contact)
* [![Buy me a coffee](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/rockhopper72)
