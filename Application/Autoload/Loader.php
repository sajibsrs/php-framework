<?php

namespace Application\Autoload;

use Exception;

class Loader {

    protected const UNABLE_TO_LOAD = "Unable to load file.";
    
    protected static array $dirs = [];
    protected static int $registered = 0;

    public function __construct($dirs = [])
    {
        self::init($dirs);
    }

    public static function init($dirs = [])
    {
        if ($dirs) {
            self::addDirs($dirs);
        }

        if (self::$registered == 0) {
            spl_autoload_register(__CLASS__ . '::autoload');
            self::$registered++;
        }
    }
    
    /**
     * Load file specified
     */
    protected static function loadFile(string $file): bool
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        
        return false;
    }

    public static function addDirs(mixed $dirs): void
    {
        if (is_array($dirs)) {
            self::$dirs = array_merge(self::$dirs, $dirs);
        } else {
            self::$dirs[] = $dirs;
        }
    }

    public static function autoload(string $class): bool
    {
        $success = false;
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        echo $name . PHP_EOL;

        foreach (self::$dirs as $dir) {
            $file = $dir . DIRECTORY_SEPARATOR . $name;

            if (self::loadFile($file)) {
                $success = true;
                break;
            }
        }

        if (! $success) {
            if (! self::loadFile(__DIR__ . DIRECTORY_SEPARATOR . $name)) {
                throw new Exception(self::UNABLE_TO_LOAD . ' ' . $class);
            }
        }

        return $success;
    }
}
