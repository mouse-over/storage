<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Application;
use Nette\Object;


/**
 * Class StorageLinkResolver
 * @package src\MouseOver\Storage\Application
 */
class StorageLinkResolver extends Object
{

    /** @var \Nette\Http\IRequest */
    private $httpRequest;

    /** @var \MouseOver\Storage\Application\StorageList */
    private $storages;

    /** @var  string */
    private $basePath;

    /**
     * StorageLinkResolver constructor.
     *
     * @param string $basePath
     * @param \Nette\Http\IRequest $httpRequest
     * @param StorageList $storages
     */
    public function __construct($basePath, \Nette\Http\IRequest $httpRequest, StorageList $storages)
    {
        $this->httpRequest = $httpRequest;
        $this->storages = $storages;
        $this->basePath = $basePath;
    }

    public function link($storageName, $fileName, $parameters = [])
    {
        $storage = $this->storages->getStorage($storageName);
        return '/'.$this->basePath . '/' . $storageName . '/' . $fileName;
    }


}