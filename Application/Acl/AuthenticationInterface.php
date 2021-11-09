<?php

namespace Application\Acl;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface AuthenticationInterface
{
    public function login(RequestInterface $request): ResponseInterface;
}
