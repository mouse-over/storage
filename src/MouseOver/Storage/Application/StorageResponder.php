<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Application;

use MouseOver;
use MouseOver\Storage\Application\StorageFileResponse;
use Nette;

/**
 * Class StorageResponder
 *
 * @package MouseOver\Storage
 */
class StorageResponder
{
    use \Nette\SmartObject;

    /** @var \MouseOver\Storage\Application\StorageList  */
    private $storages;

    /**
     * Constructor
     *
     * @param \MouseOver\Storage\Application\StorageList $storages Storage list
     */
    function __construct(MouseOver\Storage\Application\StorageList $storages)
    {
        $this->storages = $storages;
    }


    /**
     * Handle callback
     *
     * @param string $storageName Storage name
     * @param string $fileName    Storage file
     * @param array  $parameters  Additional parameters
     *
     * @throws \Nette\Application\BadRequestException
     * @return string
     */
    public function handle($storageName, $fileName, $parameters = [])
    {
        $storage = $this->storages->getStorage($storageName);
        if ($storage && $storage->fileExists($fileName)) {
            return new StorageFileResponse($storage->findFile($fileName));
        } else {
            throw new Nette\Application\BadRequestException('File does not exists!');
        }
    }

}

