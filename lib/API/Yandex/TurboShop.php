<?php

namespace rame0\API\Yandex;

class TurboShop
{
    const TYPE_NOT_INIT = -100;
    const TYPE_UNKNOWN = -10;
    const TYPE_DATA_EMPTY = -1;
    const TYPE_INCORRECT_JSON = 0;
    const TYPE_ACCEPT = 1;
    const TYPE_STATUS = 2;

    /** @var string */
    private string $token = '';
    /** @var int */
    private int $type = self::TYPE_NOT_INIT;
    /** @var string */
    private string $url_base;

    public function __construct(string $API_TOKEN, string $url_base = '')
    {
        $this->token = $API_TOKEN;
        $this->url_base = $url_base;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function process($url = ''): bool
    {
        if (empty($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
        if (!$this->isAuthorised()) {
            return false;
        }

        switch ($url) {
            case $this->url_base . '/order/accept':
                $this->type = self::TYPE_ACCEPT;
                return true;

            case $this->url_base . '/order/status':
                $this->type = self::TYPE_STATUS;
                return true;

            default:
                $this->type = self::TYPE_UNKNOWN;
                return false;

        }
    }

    /**
     * @return bool
     */
    public function isAuthorised(): bool
    {
        $headers = $this->_getHeaders();
        if (empty($headers['authorization']) || $headers['authorization'] != $this->token) {
            return false;
        }

        return true;
    }

    /**
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
            return false;
        }

        return json_decode($json, true);
    }

    public function response400()
    {
        $this->_setHTTPHeader('400 Bad Request');
        exit();
    }

    public function response403()
    {
        $this->_setHTTPHeader('403 Forbidden');
        exit();
    }

    /**
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
     * @param string $code_message
     */
    private function _setHTTPHeader(string $code_message)
    {
        header('HTTP/1.x ' . $code_message);
        header('Status: ' . $code_message);
    }

}