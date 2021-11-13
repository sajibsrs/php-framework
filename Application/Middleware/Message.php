<?php

namespace Application\Middleware;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    protected $body;
    protected $version;
    protected $httpHeaders = [];

    public function getBody()
    {
        if (!$this->body) {
            $this->body = new Stream(Constants::BODY_STREAM_DEFAULT);
        }

        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        if (!$body->isReadable()) {
            throw new InvalidArgumentException(Constants::ERROR_BODY_UNREADABLE);
        }

        $this->body = $body;
        return $this;
    }

    public function findHeader($name)
    {
        $found = false;

        foreach (array_keys($this->getHeaders()) as $header) {
            if (stripos($header, $name) !== false) {
                $found = $header;
                break;
            }
        }

        return $found;
    }

    public function getHttpHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (stripos($key, 'HTTP_') !== false) {
                $headerKey = str_ireplace('HTTP_', '', $key);
                $headers[$this->assembleHeader($headerKey)] = $value;
            } elseif (stripos($key, 'CONTENT_') !== false) {
                $header[$this->assembleHeader($key)] = $value;
            }
        }

        return $headers;
    }

    protected function assembleHeader($header)
    {
        $headerParts = explode('_', strtolower($header));
        $headerKey = ucwords(implode(' ', $headerParts));
        return str_replace(' ', '-', $headerKey);
    }

    public function getHeaders()
    {
        foreach ($this->getHttpHeaders() as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function withHeader($name, $value)
    {
        $header = $this->findHeader($name);

        if ($header) {
            $this->httpHeaders[$header] = $value;
        } else {
            $this->httpHeaders[$name] = $value;
        }

        return $this;
    }

    public function withAddedHeader($name, $value)
    {
        $header = $this->findHeader($name);

        if ($header) {
            $this->httpHeaders[$header] .= $value;
        } else {
            $this->httpHeaders[$name] = $value;
        }

        return $this;
    }

    public function withoutHeader($name)
    {
        $header = $this->findHeader($name);

        if ($header) {
            unset($this->httpHeaders[$header]);
        }

        return $this;
    }

    public function hasHeader($name)
    {
        return boolval($this->findHeader($name));
    }

    public function getHeaderLine($name)
    {
        $header = $this->findHeader($name);

        if ($header) {
            return $this->httpHeaders[$header];
        } else {
            return '';
        }
    }

    public function getHeader($name)
    {
        $line = $this->getHeaderLine($name);

        if ($line) {
            return explode(',', $line);
        } else {
            return [];
        }
    }

    public function getHeaderAsString()
    {
        $output = '';
        $headers = $this->getHeaders();

        if ($headers && is_array($headers)) {
            foreach ($headers as $key => $value) {
                if ($output) {
                    $output .= "\r\n" . $key . ': ' . $value;
                } else {
                    $output .= $key . ': ' . $value;
                }
            }
        }

        return $output;
    }

    public function getProtocolVersion()
    {
        if (!$this->version) {
            $this->version = $this->onlyVersion($_SERVER['SERVER_PROTOCOL']);
        }

        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        $this->version = $this->onlyVersion($version);
        return $this;
    }

    protected function onlyVersion($version)
    {
        if (!empty($version)) {
            return preg_replace('/[^0-9\.]', '', $version);
        } else {
            return null;
        }
    }
}
