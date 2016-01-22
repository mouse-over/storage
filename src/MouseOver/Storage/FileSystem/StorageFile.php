<?php
/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\FileSystem;

use MouseOver\Storage\IStorageFile;
use Nette\Object;


/**
 * Class StorageFile
 *
 * @package MouseOver\Storage
 */
class StorageFile extends Object implements IStorageFile
{

    /** @var  \MouseOver\Storage\FileSystem\Storage */
    private $storage;

    /** @var  string */
    private $name;

    /** @var   */
    private $handle;

    /**
     * Constructor
     *
     * @param string                               $name    File name
     * @param \MouseOver\Storage\FileSystem\Storage $storage Filesystem storage
     */
    function __construct($name, $storage)
    {
        $this->name = $name;
        $this->storage = $storage;
    }

    /**
     * Return's file name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Return's file public name
     *
     * @return string
     */
    public function getPublicName()
    {
       return $this->storage->filePublicName($this->name); 
    }

    /**
     * Return's file storage name
     *
     * @return string
     */
    public function getStorageName()
    {
        return $this->storage->getName();
    }

    /**
     * Return file real path
     *
     * @return string
     */
    public function getRealPath()
    {
        return $this->storage->filePath($this->name);
    }

    /**
     * Return's file content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->storage->fileContentType($this->name);
    }

    /**
     * Return's file content length
     *
     * @return int
     */
    public function getContentLength()
    {
        return $this->storage->fileSize($this->name);
    }

    /**
     * Return's file content. If start and end provided then returns chunk of content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->storage->readFile($this->name);
    }

    /**
     * Return's file reader
     *
     * @return \MouseOver\Storage\FileSystem\FileReader
     */
    public function getReader()
    {
       return $this->storage->createFileReader($this->name);
    }

    /**
     * Return's file metadata
     *
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->storage->getFileMetadata($this->name);
    }
}