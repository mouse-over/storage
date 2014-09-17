<?php
/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage;

/**
 * Interface IStorageFile
 *
 * @package MouseOver\Storage
 *
 * @property-read string $name          Unique name
 * @property-read string $storageName   Storage name
 * @property-read string $contentType   Content type
 * @property-read string $content       Content
 * @property-read int    $contentLength Content size
 * @property-read mixed  $metadata      Metadata
 */
interface IStorageFile
{
    /**
     * Return's file content
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Return's file reader
     *
     * @return \MouseOver\Storage\IReader
     */
    public function getReader();
}