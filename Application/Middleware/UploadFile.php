<?php

namespace Application\Middleware;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

class UploadFile implements UploadedFileInterface
{   
    protected $stream;
    protected $moved;
    protected $field;
    protected $info;
    protected $randomize;
    protected $fileName = '';

    public function __construct($field, array $info, $randomize = false)
    {
        $this->field = $field;
        $this->info = $info;
        $this->randomize = $randomize;
    }

    public function getStream()
    {
        if (! $this->stream) {
            if ($this->fileName) {
                $this->stream = new Stream($this->fileName);
            } else {
                $this->stream = new Stream($this->info['tmp_name']);
            }
        }

        return $this->stream;
    }

    public function moveTo($targetPath)
    {
        if ($this->moved) {
            throw new Exception(Constants::ERROR_FILE_ALREADY_MOVED);
        }

        if (! file_exists($targetPath)) {
            throw new InvalidArgumentException(Constants::ERROR_NO_DIR);
        }

        $tempFile = $this->info['tmp_name'] ?? false;

        if (! $tempFile || ! file_exists($tempFile)) {
            throw new Exception(Constants::ERROR_NOT_FILE);
        }

        if (! is_uploaded_file($tempFile)) {
            throw new Exception(Constants::ERROR_FILE_NOT_FOUND);
        }

        if ($this->randomize) {
            $file = bin2hex(random_bytes(8)) . '.txt';
        } else {
            $file = $this->info['name'];
        }

        $file = $targetPath . '/' . $file;
        $file = str_replace('//', '/', $file);

        if (! move_uploaded_file($tempFile, $file)) {
            throw new RuntimeException(Constants::ERROR_FILE_MOVE);
        }

        $this->fileName = $file;
        return true;
    }

    /**
     * A wrapper for *getClientFilename()* method with
     * a more convenient name
     */
    public function getFileName()
    {
        return $this->getClientFilename();
    }

    public function getSize()
    {
        return $this->info['size'] ?? null;
    }

    public function getError()
    {
        if (! $this->moved) {
            return UPLOAD_ERR_OK;
        }

        return $this->info['error'];
    }

    public function getClientFilename()
    {
        return $this->info['name'] ?? null;
    }

    public function getClientMediaType()
    {
        return $this->info['type'] ?? null;
    }
}
