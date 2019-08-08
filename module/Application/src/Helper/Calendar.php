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

    protected $client;

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
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setScopes(SCOPES);
        $client->setAuthConfig(CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
           // die;
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();

           // header('Location: '.$authUrl); exit;
            /*echo "Open the following link in your browser:\n%s\n", $authUrl;
            echo 'Enter verification code: ';*/
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
