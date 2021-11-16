<?php

namespace Application\Middleware;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    protected $uri;
    protected $method;
    protected $uriObj;

    public function __construct(
        $uri = null,
        $method = null,
        StreamInterface $body = null,
        $headers = null,
        $version = null
    )
    {
        $this->uri = $uri;
        $this->body = $body;
        $this->method = $this->checkMethod($method);
        $this->httpHeaders = $headers;
        $this->version = $this->onlyVersion($version);
    }

    protected function checkMethod($method)
    {
        if (! $method === null) {
            if (! in_array(strtolower($method), Constants::HTTP_METHODS)) {
                throw new InvalidArgumentException(Constants::ERROR_HTTP_METHOD);
            }
        }

        return $method;
    }

    public function getRequestTarget()
    {
        return $this->uri ?? Constants::REQUEST_TARGET_DEFAULT;
    }

    public function withRequestTarget($requestTarget)
    {
        $this->uri = $requestTarget;
        $this->getUri();
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $this->method = $this->checkMethod($method);
        return $this;
    }

    public function getUri()
    {
        if (! $this->uriObj) {
            $this->uriObj = new Uri($this->uri);
        }

        return $this->uriObj;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        if ($preserveHost) {
            $found = $this->findHeader(Constants::HEADER_HOST);

            if (! $found && $uri->getHost()) {
                $this->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
            }
        } elseif ($uri->getHost()) {
            $this->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
        }

        $this->uri = $uri->__toString();
        return $this;
    }
}
