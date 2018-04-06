<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage;



/**
 * Class NetteFileSystem
 *
 * <p>Uses Nette\Utils\FileSystem to provide low level filesystem methods</p>
 *
 * @package MouseOver\Storage
 */
class NetteFileSystem implements IFileSystem
{
    use \Nette\SmartObject;

    /**
     * Creates a directory.
     *
     * @param string $dir  Directory path
     * @param int    $mode Mode
     *
     * @return void
     */
    public function createDir($dir, $mode = 0777)
    {
        \Nette\Utils\FileSystem::createDir($dir, $mode);
    }

    /**
     * Copies a file or directory.
     *
     * @param string $source      Source path
     * @param string $destination Destination path
     * @param bool   $overwrite   Allow override
     *
     * @return void
     */
    public function copy($source, $destination, $overwrite = true)
    {
        \Nette\Utils\FileSystem::copy($source, $destination, $overwrite);
    }

    /**
     * Deletes a file or directory.
     *
     * @param string $path Path
     *
     * @return void
     */
    public function delete($path)
    {
        \Nette\Utils\FileSystem::delete($path);
    }

    /**
     * Renames a file or directory.
     *
     * @param string $name      Name
     * @param string $newName   New name
     * @param bool   $overwrite Allow overwrite
     *
     * @return void
     */
    public function rename($name, $newName, $overwrite = true)
    {
        \Nette\Utils\FileSystem::rename($name, $newName, $overwrite);
    }

    /**
     * Writes a string to a file.
     *
     * @param string $file    File path
     * @param string $content Content to write
     * @param int    $mode    File mode
     *
     * @return bool
     */
    public function write($file, $content, $mode = 0666)
    {
        return \Nette\Utils\FileSystem::write($file, $content, $mode);
    }

    /**
     * Is path absolute?
     *
     * @param string $path Path
     *
     * @return bool
     */
    public function isAbsolute($path)
    {
        return \Nette\Utils\FileSystem::isAbsolute($path);
    }

    /**
     * Is file name readable?
     *
     * @param string $fileName File name
     *
     * @return bool
     */
    public function isReadable($fileName)
    {
        return is_readable($fileName);
    }

    /**
     * Read file
     *
     * @param string $filePath File path
     *
     * @return string file content
     */
    public function read($filePath)
    {
        return file_get_contents($filePath);
    }

    /**
     * Return's file content type
     *
     * @param string $filePath File path
     *
     * @return string
     */
    public function fileContentType($filePath)
    {
        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
        return strpos($type, '/') ? $type : 'application/octet-stream';
    }


    /**
     * Return's file size
     *
     * @param string $filePath File path
     *
     * @return int
     */
    public function fileSize($filePath)
    {
        return filesize($filePath);
    }

    /**
     * SplFileObject factory
     *
     * @param string   $file_name        The file to read.
     * @param string   $open_mode        [optional] The mode in which to open the file. See fopen() for a list of allowed modes.
     * @param boolean  $use_include_path [optional] Whether to search in the include_path for filename.
     * @param resource $context          [optional] A valid context resource created with stream_context_create().
     *
     * @return \SplFileObject
     */
    public function createFileObject ($file_name, $open_mode = 'r', $use_include_path = false, $context = null)
    {
        if ($context !== null) {
            return new \SplFileObject($file_name, $open_mode, $use_include_path, $context);
        }
        return new \SplFileObject($file_name, $open_mode, $use_include_path);
    }
}
