<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage;


/**
 * Interface IFileSystem
 *
 * <p>Low level filesystem methods (interface inspired by Nette\Utils\FileSystem)</p>
 *
 * @package MouseOver\Storage
 */
interface IFileSystem
{
    /**
     * Creates a directory.
     *
     * @param string $dir  Directory path
     * @param int    $mode Mode
     *
     * @return void
     */
    public function createDir($dir, $mode = 0777);

    /**
     * Copies a file or directory.
     *
     * @param string $source      Source path
     * @param string $destination Destination path
     * @param bool   $overwrite   Allow override
     *
     * @return void
     */
    public function copy($source, $destination, $overwrite = true);

    /**
     * Deletes a file or directory.
     *
     * @param string $path Path
     *
     * @return void
     */
    public function delete($path);

    /**
     * Renames a file or directory.
     *
     * @param string $name      Name
     * @param string $newName   New name
     * @param bool   $overwrite Allow overwrite
     *
     * @return void
     */
    public function rename($name, $newName, $overwrite = true);

    /**
     * Writes a string to a file.
     *
     * @param string $file    File path
     * @param string $content Content to write
     * @param int    $mode    File mode
     *
     * @return bool
     */
    public function write($file, $content, $mode = 0666);

    /**
     * Is path absolute?
     *
     * @param string $path Path
     *
     * @return bool
     */
    public function isAbsolute($path);

    /**
     * Is file name readable?
     *
     * @param string $fileName File name
     *
     * @return bool
     */
    public function isReadable($fileName);

    /**
     * Read file
     *
     * @param string $filePath File path
     *
     * @return string file content
     */
    public function read($filePath);

    /**
     * Return's file content type
     *
     * @param string $filePath File path
     *
     * @return string
     */
    public function fileContentType($filePath);

    /**
     * Return's file size
     *
     * @param string $filePath File path
     *
     * @return int
     */
    public function fileSize($filePath);
}