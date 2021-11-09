<?php

namespace Application\Autoload;

use Exception;

class Loader {

    protected const UNABLE_TO_LOAD = "Unable to load file.";
    
    /**
     * Keep track of registered loader. If loader is already 
     * registered, this value will get an incerement and won't
     * run the loader again.
     * 
     * @var int $registered
     */
    protected static int $registered = 0;

    /**
     * Keep track of directories. Directories added here will be
     * searched for files.
     * 
     * @var array $dirs
     */
    protected static array $dirs = [];

    public function __construct($dirs = [])
    {
        self::init($dirs);
    }

    /**
     * Initialize autoloader with directories and then run autoload method.
     * 
     * @param array $dirs Directories to look for files
     */
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
     * Autoload files.
     * 
     * @param string $class Class that is being autoloaded
     * @return bool
     * @throws \Exception Exception on failure
     */
    public static function autoload(string $class): bool
    {
        $success = false;
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

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

    /**
     * Add directories
     * 
     * @param array|string $dirs String for single and array for multiple directories
     */
    public static function addDirs(mixed $dirs): void
    {
        if (is_array($dirs)) {
            self::$dirs = array_merge(self::$dirs, $dirs);
        } else {
            self::$dirs[] = $dirs;
        }
    }
    
    /**
     * Load specified file
     * 
     * @param string $file Full qualified file name
     * @return bool
     */
    protected static function loadFile(string $file): bool
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        
        return false;
    }
}
