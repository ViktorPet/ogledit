<?php
namespace Application\Controller;

/**
 * Class for integration of third-party chat system.
 */
class ChatProcessor {

    CONST URL = 'http://5.189.138.212:7000/in.pl';
    CONST AUTH_ID = 'bf1c9b816245d28c9a9219f2554aa000';

    /**
     * Executes command to the service, posting set of parameters and returns object.
     *
     * @param $postFieldsArr array
     * @return mixed
     */
    private static function executeCommand($postFieldsArr) {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL            => self::URL,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $postFieldsArr
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    /**
     * Gets fresh token from the API for particular page.
     */

    /**
     * Gets fresh token from the API for particular page.
     *
     * @param $pageId - Page Id
     */
    public static function getToken($pageId) {
        $result = self::executeCommand(array(
            'cmd' => 'get_token',
            'auth_id' => 'bf1c9b816245d28c9a9219f2554aa000',
            'client_page_id' => $pageId
        ));

        return $result->token;
    }
}