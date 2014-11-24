<?php
class Synchronizer
{
    protected static $default_directory = null;
    protected static $instances = array();

    public static function setDefaultDirectory($directory)
    {
        $old = self::$default_directory;
        self::$default_directory = $directory;
        return $old;
    }
    
    public static function getDefaultDirectory()
    {
        return self::$default_directory;
    }

    public static function getInstance($synchronizable_directory = null)
    {
        if ($synchronizable_directory === null && self::$default_directory === null) {
            throw new Exception('Synchronizer: No default synchronizable directory has been set');
        }

        if ($synchronizable_directory === null) {
            $synchronizable_directory = self::$default_directory;
        }

        if (!isset(self::$instances[$synchronizable_directory])) {
            self::$instances[$synchronizable_directory] = new self($synchronizable_directory);
        }
        return self::$instances[$synchronizable_directory];
    }

    protected $classes = array();

    private function __construct($directory)
    {
        $files = glob(rtrim($directory, '/') . '/*.php');
        foreach ($files as $file) {
            $class = basename(basename($file, '.class.php'), '.php');

            require_once $file;

            if (class_exists($class) && is_subclass_of($class, 'SynchronizableObject')) {
                $this->classes[] = $class;
            }
        }
    }

    public function getObjects($local = true, $since = 0, $version = null)
    {
        $objects = array();
        foreach ($this->classes as $class) {
            $objects = array_merge($objects, $class::findAll($local, $since, $version));
        }
        return $objects;
    }

    public function getConflicted($studip_version = null)
    {
        
    }
    
    public function getLatestStudipVersion($language = null)
    {
        $version = 0;
        foreach ($this->classes as $class) {
            $version = max($version, $class::getLatestStudipVersion($language));
        }
        return $version;
    }
}