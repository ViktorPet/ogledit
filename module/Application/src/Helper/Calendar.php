<?php

namespace Application\Helper;

use Zend\Mail\Exception\RuntimeException;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('SCOPES', implode(' ', array(Google_Service_Calendar::CALENDAR)));

/**
 * Description of Calendar
 *
 */
class Calendar {
    protected $session = [];
    protected $client;

    public function __construct () {
      $this->session = &$_SESSION;
    }
    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    public function expandHomeDirectory($path) {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

    public function getClient() {
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setClientId ('902268860778-gu1alk5e5ddod596gji72cma75ee347d.apps.googleusercontent.com');
        $client->setClientSecret ('vq8nrD4ERD0A2HovtCkZY71G');
        $client->setScopes(SCOPES);
        $client->setAuthConfig(CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setRedirectUri ($redirect_uri);
        $client->authenticate ('6342c3bf2b846d984f97136050d403b1b8a088c7');

        if (array_key_exists ('logout', $_REQUEST)) {
          unset ($this->session ['id_token_token']);
        }

        //$token = $client->fetchAccessTokenWithAuthCode ('vq8nrD4ERD0A2HovtCkZY71G');

        //$this->session ['id_token_token'] = $token;

        if ($client->getAccessToken()) {
            //$token_data = $client->verifyIdToken();
        }
        echo '<pre>';
        var_dump ($client->createAuthUrl ());
        echo print_r (get_class_methods ($client), true);
        echo print_r ($client, true);
        echo '<pre>';


        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            die;
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            echo "Open the following link in your browser:\n%s\n", $authUrl;
            echo 'Enter verification code: ';
            $authCode = trim(AUTH_CODE);

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if(!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0777, true);
            }

            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);

        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken(REFRESH_TOKEN);
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }



        return $client;
    }
}
