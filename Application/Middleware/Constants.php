<?php

namespace Application\Middleware;

class Constants
{
    const HEADER_HOST           = 'Host';
    const HEADER_CONTENT_TYPE   = 'Content-type';
    const HEADER_CONTENT_LENGTH = 'Content-Length';

    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_PUT    = 'put';
    const METHOD_DELETE = 'delete';

    const HTTP_METHODS  = ['get', 'put', 'post', 'delete'];

    const STANDARD_PORTS = [
        'ftp'   => 21,
        'ssh'   => 22,
        'http'  => 80,
        'https' => 443
    ];

    const CONTENT_TYPE_FORM_ENCODED = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE_MULTI_FORM   = 'multipart/form-data';
    const CONTENT_TYPE_JSON         = 'application/json';
    const CONTENT_TYPE_HAL_JSON     = 'application/hal+json';

    const STATUS_CODE_DEFAULT       = 200;
    const BODY_STREAM_DEFAULT       = 'php://input';
    const REQUEST_TARGET_DEFAULT    = '/';

    const MODE_READ     = 'r';
    const MODE_WRITE    = 'w';

    const ERROR_BAD = 'ERROR: ';
    const ERROR_UNKNOWN = 'ERROR: Unknown';

    const STATUS_CODES = [
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        408 => 'Request Timeout',
        418 => 'I\'m A Teapot',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        504 => 'Gateway Timeout'
    ];
}
