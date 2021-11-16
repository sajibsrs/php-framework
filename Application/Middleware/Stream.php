<?php

namespace Application\Middleware;

use Psr\Http\Message\StreamInterface;
use RuntimeException;
use SplFileInfo;
use Throwable;

class Stream implements StreamInterface
{
    protected $stream;
    protected $metadata;
    protected $info;

    public function __construct($input, $mode = Constants::MODE_READ)
    {
        $this->stream = fopen($input, $mode);
        $this->metadata = stream_get_meta_data($this->stream);
        $this->info = new SplFileInfo($input);
    }

    public function getStream()
    {
        return $this->stream;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function read($length)
    {
        if (! fread($this->stream, $length)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    public function write($string)
    {
        if (! fwrite($this->stream, $string)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    public function rewind()
    {
        if (! rewind($this->stream)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    public function eof()
    {
        return feof($this->stream);
    }

    public function tell()
    {
        try {
            return ftell($this->stream);
        } catch (Throwable $e) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        try {
            fseek($this->stream, $offset, $whence);
        } catch (Throwable $e) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }
    }

    public function close()
    {
        if ($this->stream) {
            fclose($this->stream);
        }
    }

    public function detach()
    {
        return $this->close();
    }

    public function getMetadata($key = null)
    {
        if ($key) {
            return $this->metadata[$key] ?? null;
        } else {
            return $this->metadata;
        }
    }

    public function getSize()
    {
        return $this->info->getSize();
    }

    public function isSeekable()
    {
        return boolval($this->metadata['seekable']);
    }

    public function isWritable()
    {
        return $this->info->isWritable();
    }

    public function isReadable()
    {
        return $this->info->isReadable();
    }

    public function getContents()
    {
        ob_start();

        if (! fpassthru($this->stream)) {
            throw new RuntimeException(Constants::ERROR_BAD . __METHOD__);
        }

        return ob_get_clean();       
    }

    public function __toString()
    {
        $this->rewind();
        return $this->getContents();
    }
}
