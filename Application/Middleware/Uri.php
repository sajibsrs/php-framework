<?php

namespace Application\Middleware;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    protected string $uri;
    protected $uriParts, $queryParams = [];

    public function __construct(string $uri)
    {
        $this->uriParts = parse_url($uri);

        if (! $this->uriParts) {
            throw new InvalidArgumentException(Constants::ERROR_INVALID_URI);
        }

        $this->uri = $uri;
    }

    public function getScheme(): string
    {
        return strtolower($this->uriParts['scheme']) ?? '';
    }

    public function getAuthority()
    {
        $authority = '';
        
        if (! empty($this->getUserInfo())) {
            $authority .= $this->getUserInfo() . '@';
        }

        $authority .= $this->uriParts['host'] ?? '';

        if (! empty($this->uriParts['port'])) {
            $authority .= ':' . $this->uriParts['port'];
        }

        return $authority;
    }

    public function getUserInfo()
    {
        if (empty($this->uriParts['user'])) {
            return '';
        }

        $userInfo = $this->uriParts['user'];

        if (!empty($this->uriParts['pass'])) {
            $userInfo .= ':' . $this->uriParts['pass'];
        }

        return $userInfo;
    }

    public function getHost()
    {
        if (empty($this->uriParts['host'])) {
            return '';
        }

        return strtolower($this->uriParts['host']);
    }

    public function getPort()
    {
        if (empty($this->uriParts['port'])) {
            return null;
        } else {
            if ($this->getScheme()) {
                if ($this->uriParts['port'] == Constants::STANDARD_PORTS[$this->getScheme()]) {
                    return null;
                }
            }
            return (int) $this->uriParts['port'];
        }
    }

    public function getPath()
    {
        if (empty($this->uriParts['path'])) {
            return '';
        }

        return implode('/', array_map('rawurlencode', explode('/', $this->uriParts['path'])));
    }

    public function getQueryParams($reset = false)
    {
        if ($this->queryParams && ! $reset) {
            return $this->queryParams;
        }

        $this->queryParams;

        if (! empty($this->uriParts['query'])) {
            foreach (explode('&', $this->uriParts['query']) as $keyPair) {
                list($param, $value) = explode('=', $keyPair);
                $this->queryParams[$param] = $value;
            }
        }

        return $this->queryParams;
    }

    public function getQuery()
    {
        if (! $this->getQueryParams()) {
            return '';
        }

        $query = '';

        foreach ($this->getQueryParams() as $key => $value) {
            $query .= rawurlencode($key) . '=' . rawurlencode($value) . '&';
        }

        return substr($query, 0, -1);
    }

    public function getFragment()
    {
        if (empty($this->uriParts['fragment'])) {
            return '';
        }

        return rawurlencode($this->uriParts['fragment']);
    }

    public function withScheme($scheme)
    {
        if (empty($scheme) && $this->getScheme()) {
            unset($this->uriParts['scheme']);
        } else {
            if (isset(Constants::STANDARD_PORTS[strtolower($scheme)])) {
                $this->uriParts['scheme'] = $scheme;
            } else {
                throw new InvalidArgumentException(Constants::ERROR_BAD . __METHOD__);
            }
        }

        return $this;
    }

    public function withHost($host)
    {
        if (empty($host) && $this->getHost()) {
            unset($this->uriParts['host']);
        } else {
            $this->uriParts['host'] = $host;
        }

        return $this;
    }

    public function withPort($port)
    {
        if (empty($port) && $this->getPort()) {
            unset($this->uriParts['port']);
        } else {
            $this->uriParts['port'] = $port;
        }

        return $this;
    }

    public function withPath($path)
    {
        if (empty($path) && $this->getPath()) {
            unset($this->getPath['path']);
        } else {
            $this->uriParts['path'] = $path;
        }

        return $this;
    }

    public function withUserInfo($user, $password = null)
    {
        if (empty($user) && $this->getUserInfo()) {
            unset($this->uriParts['user']);
        } else {
            $this->uriParts['user'] = $user;

            if ($password) {
                $this->uriParts['pass'] = $password;
            }
        }

        return $this;
    }

    public function withQuery($query)
    {
        if (empty($query) && $this->getQuery()) {
            unset($this->uriParts['query']);
        } else {
            $this->uriParts['query'] = $query;
        }

        $this->getQueryParams(true);
        return $this;
    }

    public function withFragment($fragment)
    {
        if (empty($fragment) && $this->getFragment()) {
            unset($this->uriParts['fragment']);
        } else {
            $this->uriParts['fragment'] = $fragment;
        }

        return $this;
    }

    public function __toString()
    {
        $uri = $this->getScheme() ? $this->getScheme() . '://' : '';

        if ($this->getAuthority()) {
            $uri .= $this->getAuthority();
        } else {
            $uri .= $this->getHost() ?: '';
            $uri .= $this->getPort() ? ':' . $this->getPort() : '';
        }

        $path = $this->getPath();

        if ($path) {
            if ($path[0] != '/') {
                $uri .= '/' . $path;
            } else {
                $uri .= $path;
            }
        }

        $uri .= $this->getQuery() ? '?' . $this->getQuery() : '';
        $uri .= $this->getFragment() ? '#' . $this->getFragment() : '';
        return $uri;
    }
}
