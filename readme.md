## Big Blue Meeting Community Edition

![big blue meeting screenshot](https://raw.githubusercontent.com/bigbluemeeting/bigbluemeeting/master/public/screenshot/Screenshot_2020-07-01%20Meetings%20List%20(2).png)


Big Blue Meeting CE is an opensource, free PHP-based frontend for BigBlueButton similar to Greenlight written in Laravel 7.

## Status

Application is currently usable, but in beta with a few issues.

## Usage

* Create a MySQL database
* Download composer https://getcomposer.org/download/
* Pull this project from git provider.
* Rename .env.example file to .env inside your project root and fill the database information.
* Open the console and cd your project root directory
* Run composer install or php composer.phar install
* Run php artisan key:generate
* Run php artisan migrate
* Run php artisan db:seed to run seeders, if any.
* Run php artisan serve (for development)


## Sponsorship

Big Blue Meeting CE is completely funded by Big Blue Meeting (https://www.bigbluemeeting.com) and Etopian Inc. (https://www.etopian.com). However we welcome sponsorships and contributions from any other parties.

Release history
- Beta 1


## License
AGPL 3.0
