<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage;


 /**
 * Interface IStorage
  *
 * @package MouseOver\Storage
 */
interface IStorage
{

    /**
     * Return's storage name
     *
     * @return string Storage name
     */
    public function getName();

    /**
     * Write file to storage
     *
     * @param string $name     Unique name
     * @param string $content  File content
     * @param array  $metadata Optional metadata
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function writeFile($name, $content, $metadata = []);

    /**
     * Update file metadata
     *
     * @param string $name     Unique name
     * @param array  $metadata Metadata
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function saveFileMetadata($name, $metadata = []);

    /**
     * Return's file with given name
     *
     * @param string $name Unique name
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function findFile($name);

    /**
     * Return's files by using where conditions
     *
     * @param array $where Where conditions
     *
     * @return \MouseOver\Storage\IStorageFile[]
     */
    public function findFiles($where);

    /**
     * Remove file
     *
     * @param string $name Unique file name
     *
     * @return mixed
     */
    public function removeFile($name);

    /**
     * Return's bool true if file exists
     *
     * @param string $name File name
     *
     * @return bool true if exists
     */
    public function fileExists($name);

    /**
     * Create file reader
     *
     * @param string $name File name
     *
     * @return mixed
     */
    public function createFileReader($name);


}