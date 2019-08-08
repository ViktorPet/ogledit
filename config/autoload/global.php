<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

define('CREDENTIALS_PATH', BASE_PATH . '/config/calendar-php-quickstart_dev.json');
define('CLIENT_SECRET_PATH', BASE_PATH . '/config/client_secret_dev.json');
define('AUTH_CODE', '4/nQHpLr6y80LMurpUsCgnRoukpC7rVDoyqyz0SVQWhPprprz1Lnv9FM3YdHc8mKhZ1gjjFu8epa6VrdIk7XrjLgM');
define('REFRESH_TOKEN', '1\/9VWj1FMw8p8xSnUuiBQJ4kKKEqbh8ZSGmvvsgJkovtg');

define('CHAT_PATH', 'https://chat.ogledi.bg');
define('SITE_URL', 'https://ogledi.bg/');


//For Testing
//define('SITE_URL', 'http://dev.ogledi.local/');

return [
    // ...
];
