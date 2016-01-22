<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\FileSystem;

use MouseOver;
use MouseOver\Storage\FileSystem\StorageFile;
use MouseOver\Storage\IFileSystem;
use MouseOver\Storage\IMetadataStorage;
use MouseOver\Storage\IStorage;
use Nette\Utils\Finder;
use Nette\Utils\Strings;

/**
 * Description of Storage
 *
 * @author vaclav
 */
class Storage implements IStorage
{

    /** @var string */
    protected $dir;

    /** @var string */
    protected $name;

    /** @var  \MouseOver\Storage\IMetadataStorage */
    protected $metadataStorage;

    /** @var \MouseOver\Storage\IFileSystem  */
    protected $fileSystem;

    /**
     * Constructor
     *
     * @param string                              $name            Storage name
     * @param string                              $dir             Storage dir
     * @param \MouseOver\Storage\IFileSystem      $fileSystem      File system utils
     * @param \MouseOver\Storage\IMetadataStorage $metadataStorage Metadata storage
     */
    public function __construct(
        $name,
        $dir,
        IFileSystem $fileSystem,
        IMetadataStorage $metadataStorage = null
    ) {
        $this->dir = $dir;
        $this->name = $name;
        $this->fileSystem = $fileSystem;
        $this->setMetadataStorage($metadataStorage);
    }

    /**
     * Set's metadata storage
     *
     * @param \MouseOver\Storage\IMetadataStorage $metadataStorage Metadata storage
     *
     * @return void
     */
    public function setMetadataStorage($metadataStorage)
    {
        $this->metadataStorage = $metadataStorage;
    }

    /**
     * Return's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return's file path
     *
     * @param string $name File name
     *
     * @return string
     */
    public function filePath($name)
    {
        return "$this->dir/$this->name/$name";
    }

    /**
     * Return's storage directory
     *
     * @return string
     */
    public function getStorageDir()
    {
        return "$this->dir/$this->name";
    }

    /**
     * Write file to storage
     *
     * @param string $name     Unique name
     * @param string $content  File content
     * @param array  $metadata Optional metadata
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function writeFile($name, $content, $metadata = [])
    {
        $this->fileSystem->write($this->getStorageDir() . '/' . $name, $content);
        $this->saveFileMetadata($name, $metadata);
        return $this->findFile($name);
    }

    /**
     * Update file metadata
     *
     * @param string $name     Unique name
     * @param array  $metadata Metadata
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function saveFileMetadata($name, $metadata = [])
    {
        $file = $this->findFile($name);
        if ($metadata && $this->metadataStorage) {
            $this->metadataStorage->saveFileMetadata($file, $metadata);
        }
        return $file;
    }

    /**
     * Return's file metadata
     *
     * @param string $name File name
     *
     * @return null
     * @return mixed
     */
    public function getFileMetadata($name)
    {
        if ($this->metadataStorage) {
            $file = $this->findFile($name);
            return $this->metadataStorage->findFor($file);
        }
        return null;
    }

    /**
     * Return's file with given name
     *
     * @param string $name Unique name
     *
     * @return \MouseOver\Storage\IStorageFile
     */
    public function findFile($name)
    {
        $path = $this->filePath($name);
        if ($this->fileSystem->isReadable($path)) {
            return new StorageFile($name, $this);
        }
        return null;
    }

    /**
     * Return's files by using where conditions
     *
     * @param array $where Where conditions
     *
     * @return \MouseOver\Storage\IStorageFile[]
     */
    public function findFiles($where)
    {
        $documents = array();
        $path = $this->filePath($where);
        $files = Finder::findFiles('*')->in($path);
        foreach ($files as $file) {
            if ($file instanceof \SplFileInfo && $file->isFile()) {
                $id = Strings::replace($file->getRealPath(), $this->getStorageDir(), '', 1);
                $documents[] = $this->findFile($id);
            }
        }
        return $documents;
    }

    /**
     * Remove file
     *
     * @param string $name Unique file name
     *
     * @return mixed
     */
    public function removeFile($name)
    {
        $file = $this->findFile($name);
        if ($file) {
            $this->fileSystem->delete($this->filePath($name));
            if ($this->metadataStorage) {
                $this->metadataStorage->removeFile($file);
            }
        }
    }


    /**
     * Return's file content
     *
     * @param string $name File name
     *
     * @return string
     */
    public function readFile($name)
    {
        return $this->fileSystem->read($this->filePath($name));
    }

    /**
     * Create file reader
     *
     * @param string $name File name
     *
     * @return \MouseOver\Storage\IReader|boolean Reader instance or bool false if file not exists
     */
    public function createFileReader($name)
    {
        if ($this->fileExists($name)) {
            return new FileReader($this->filePath($name));
        }

        return false;
    }


    /**
     * Return's bool true if file exists
     *
     * @param string $name File name
     *
     * @return bool true if exists
     */
    public function fileExists($name)
    {
        $path = $this->filePath($name);
        return $this->fileSystem->isReadable($path);
    }

    public function fileContentType($name)
    {
        $path = $this->filePath($name);
        return $this->fileSystem->fileContentType($path);
    }
    
    public function filePublicName($name)
    {
        return $name;
    }

    /**
     * Return's file size
     *
     * @param string $name File name
     *
     * @return int file size
     */
    public function fileSize($name)
    {
        $path = $this->filePath($name);
        return $this->fileSystem->fileSize($path);
    }


    /**
     * Rename file
     *
     * @param string $name    Unique file name of existing file
     * @param string $newName New file name
     * @param bool   $overwrite Allow overwrite
     *
     * TODO: change metadata to
     *
     * @return void
     */
    public function renameFile($name, $newName, $overwrite = true)
    {
        $path = $this->filePath($name);
        $newPath = $this->filePath($newName);
        $this->fileSystem->rename($path, $newPath, $overwrite);
    }

}