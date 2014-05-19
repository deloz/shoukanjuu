<?php

namespace Shoukanjuu;

use Shoukanjuu\Exception\FileNotFound;

class File
{
    public static function exists($filename)
    {
        return file_exists($filename);
    }

    public static function put($filename, $data)
    {
        return file_put_contents($filename, $data);
    }

    public static function copy($source, $target)
    {
        return copy($source, $target);
    }

    public static function get($filename)
    {
        if (self::isFile($filename)) {
            return file_get_contents($filename);
        }

        throw new FileNotFound(sprintf('File [%s] not found.', $filename));
    }

    public static function delete($filename)
    {
        return @unlink($filename);
    }

    public static function type($filename)
    {
        return filetype($filename);
    }

    public static function isFile($filename)
    {
        return is_file($filename);
    }

    public static function getRequire($filename)
    {
        if (self::isFile($filename)) {
            return require $filename;
        }

        throw new FileNotFound(sprintf('File [%s] not found.', $filename));
        
    }
}
