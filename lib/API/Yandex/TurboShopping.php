<?php

namespace rame0\API\Yandex;

class TurboShopping
{
    const TYPE_NOT_INIT = -2;
    const TYPE_UNKNOWN = -1;
    const TYPE_ACCEPT = 1;
    const TYPE_STATUS = 2;

    /** @var string */
    private string $token = '';
    /** @var int */
    private int $type = self::TYPE_NOT_INIT;
    /** @var array */
    private array $post_data = [];
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
     * @return array
     */
    public function getPostData(): array
    {
        return $this->post_data;
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
            $this->_setHTTPHeader('403 Forbidden');
            return false;
        }

        $this->post_data = json_decode(file_get_contents('php://input'), true);

        switch ($url) {
            case $this->url_base . '/order/accept':
                $this->type = self::TYPE_ACCEPT;
                return true;
                break;
            case $this->url_base . '/order/status':
                $this->type = self::TYPE_STATUS;
                return true;
                break;
            default:
                $this->type = self::TYPE_UNKNOWN;
                $this->_setHTTPHeader('400 Bad Request');
                return false;
                break;
        }
    }

    /**
     * @return bool
     */
    public function isAuthorised(): bool
    {
        $headers = $this->_getHeaders();
        if ($headers['authorization'] !== $this->token) {
            return false;
        }

        return true;
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