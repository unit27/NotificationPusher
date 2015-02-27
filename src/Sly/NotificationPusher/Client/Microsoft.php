<?php
/*******************************************************************************
 * Name: Notification pusher / Microsoft client
 * Version: 1.0
 * Author: Przemyslaw Ankowski (przemyslaw.ankowski@gmail.com)
 ******************************************************************************/


// Default namespace
namespace Sly\NotificationPusher\Client;


/**
 * Class Microsoft
 *
 * @package Sly\NotificationPusher\Client
 */
class Microsoft
{
    /**
     * Send message to microsoft windows phone user
     *
     * @param $uri
     * @param $message
     * @return array
     */
    public function send($uri, $message) {
        // Build headers
        $httpHeader = [
            "Content-Type: text/xml",
            "Accept: application/*",
            "X-NotificationClass: 2",
            "X-WindowsPhone-Target: toast"
        ];

        // Create XML message
        $message =	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<wp:Notification xmlns:wp=\"WPNotification\">" .
            "<wp:Toast>" .
            "<wp:Text1></wp:Text1>" .
            "<wp:Text2>" . \htmlspecialchars($message) . "</wp:Text2>" .
            "</wp:Toast>" .
            "</wp:Notification>";

        // Create cURL handler
        $HttpRequest = \curl_init();

        // Set cURL options
        \curl_setopt($HttpRequest, CURLOPT_HEADER, true);
        \curl_setopt($HttpRequest, CURLOPT_HTTPHEADER, $httpHeader);
        \curl_setopt($HttpRequest, CURLOPT_POST, true);
        \curl_setopt($HttpRequest, CURLOPT_POSTFIELDS, $message);
        \curl_setopt($HttpRequest, CURLOPT_URL, $uri);
        \curl_setopt($HttpRequest, CURLOPT_RETURNTRANSFER, 1);

        // Execute http request
        $response = \curl_exec($HttpRequest);

        // Close http request
        \curl_close($HttpRequest);

        // Create result array
        $result = [];

        // Build result
        foreach (\explode("\n", $response) as $line) {
            $tab = \explode(":", $line, 2);

            if (\count($tab) == 2) {
                $result[$tab[0]] = \trim($tab[1]);
            }
        }

        // Return result
        return $result;
    }
}
