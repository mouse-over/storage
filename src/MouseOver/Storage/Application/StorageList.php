<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Application;

use MouseOver\Storage\IStorage;
use Nette\ArrayList;


/**
 * Class StorageList
 * @package MouseOver\Storage
 */
class StorageList extends ArrayList
{
    /**
     * Register storage
     *
     * @param \MouseOver\Storage\IStorage $storage Storage instance
     */
    public function register(IStorage $storage)
    {
        $this->offsetSet(null, $storage);
    }

    /**
     * Return's storage with given name
     *
     * @param string $storageName Storage name
     *
     * @return null|\MouseOver\Storage\IStorage
     */
    public function getStorage($storageName)
    {
        foreach ($this as $storage) {
            if ($storage->getName() === $storageName) {
                return $storage;
            }
        }
        return null;
    }
}