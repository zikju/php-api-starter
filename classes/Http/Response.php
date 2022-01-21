<?php

namespace zikju\Shared\Http;

class Response
{
    /**
     * @param string $status
     * @param string $message
     * @param array $payloadArray
     * @param int $code
     */
    public static function send (
        string $status,
        string $message = '',
        ?array $payloadArray = [],
        ?int $code = 0
    ) {

        $responseArray = [];

        // Set 'status' (REQUIRED): 'ok' or 'error'
        $responseArray['status'] = $status;

        // Set 'message' if necessary
        if (isset($message) && !empty($message)) {
            $responseArray['message'] = $message;
        }

        // Set 'payload' if necessary
        if (isset($payloadArray) && !empty($payloadArray)) {
            $responseArray['payload'] = $payloadArray;
        }

        // If 'code' parameter is empty, then use defaults
        if($code === 0) {
            switch ($status) {
                case 'ok':
                    $code = 200; // HTTP/1.1 200 OK
                    break;
                case 'error':
                    $code = 400; // Bad Request
                    break;
            }
        }
        $responseArray['code'] = $code;

        /**
         * For OPTIONS requests - always return '200 OK' code!!!
         *
         * When the browser sends cross server requests
         * it firsts sends an OPTIONS request
         * to make sure it is valid and it can send the "real" request.
         * After it gets a proper and valid response from OPTIONS,
         * only then it sends the "real" request.
         * */
        if (Request::getMethod() === "OPTIONS") {
            http_response_code(200);
        } else {
            http_response_code($code);
        }


        // print response
        die(json_encode($responseArray));
    }
}