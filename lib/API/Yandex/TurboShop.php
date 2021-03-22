<?php

namespace rame0\API\Yandex;

class TurboShop
{
    /** @var string */
    private string $token = '';

    /**
     * TurboShop constructor.
     * @param $API_TOKEN
     */
    public function __construct($API_TOKEN)
    {
        $this->token = $API_TOKEN;
    }

    /**
     * Check if token correct
     * @return bool
     */
    public function isAuthorised(): bool
    {
        $headers = $this->_getHeaders();
        if (empty($headers['authorization']) || $headers['authorization'] != $this->token) {
            error_log('Unauthorised');
            return false;
        }

        return true;
    }

    /**
     * Get POST body
     * @param string $fake_post_body
     * @return array|mixed
     * Value if decoded
     * NULL if JSON can't be parsed or POST body = 'NULL'
     */
    public function parsePostBody($fake_post_body = '')
    {
        if (!empty($fake_post_body)) {
            $json = $fake_post_body;
        } else {
            $json = file_get_contents('php://input');
        }

        if (empty($json)) {
            error_log('Can\'t read POST body');
            return false;
        }

        $array = json_decode($json, true);

        if (empty($array) || empty($array['order'])) {
            error_log('Can\'t parse JSON');
            return false;
        }
        return $array['order'];
    }

    /**
     * Send Out Of Date respond
     * @param string $debug_message
     */
    public function respondOutOfDate($debug_message = '')
    {
        $this->_setHTTPHeader('200 Ok');
        error_log('Sent out of date response' . (empty($debug_message) ? '' : ': ' . $debug_message));
        echo '{"order":{"accepted":false,"reason":"OUT_OF_DATE"}}';
        exit();
    }

    /**
     * Send Accept respond
     * @param $id
     */
    public function respondAccept($id)
    {
        $this->_setHTTPHeader('200 Ok');
        echo '{"order":{"accepted":true,"id":"' . $id . '"}}';
        exit();
    }

    /**
     * Send 200 OK respond
     */
    public function respond200()
    {
        $this->_setHTTPHeader('200 OK');
        exit();
    }

    /**
     * Send 400 respond
     * @param string $debug_message
     */
    public function respond400($debug_message = '')
    {
        $this->_setHTTPHeader('400 Bad Request');
        error_log('Sent bad request header' . (empty($debug_message) ? '' : ': ' . $debug_message));
        exit();
    }

    /**
     * Send 403 respond
     * @param string $debug_message
     */
    public function respond403($debug_message = '')
    {
        $this->_setHTTPHeader('403 Forbidden');
        error_log('Sent forbidden header' . (empty($debug_message) ? '' : ': ' . $debug_message));
        exit();
    }

    /**
     * Send 500 respond
     * @param string $debug_message
     */
    public function respond500($debug_message = '')
    {
        $this->_setHTTPHeader('500 Internal Server Error');
        error_log('Sent 500 Internal Server Error header' . (empty($debug_message) ? '' : ': ' . $debug_message));
        exit();
    }

    /**
     * Get request headers
     * @return array
     */
    private function _getHeaders(): array
    {
        if (!function_exists('getallheaders')) {
            if (!is_array($_SERVER)) {
                return [];
            }

            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
                }
            }
        } else {
            $request_headers = getallheaders();
            $headers = [];
            foreach ($request_headers as $header => $value) {
                $headers[strtolower($header)] = $value;
            }
        }

        return $headers;
    }

    /**
     * Set response headers
     * @param string $code_message
     */
    private function _setHTTPHeader(string $code_message)
    {
        header('HTTP/1.x ' . $code_message);
        header('Status: ' . $code_message);
    }

}