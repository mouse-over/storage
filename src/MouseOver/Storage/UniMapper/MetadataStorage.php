<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\UniMapper;

use MouseOver\Storage\IMetadataStorage;
use Nette\DateTime;
use UniMapper\Repository;


/**
 * Class MetadataStorage
 *
 * @package MouseOver\Storage\Postgres
 */
class MetadataStorage implements IMetadataStorage
{
    use \Nette\SmartObject;

    /** @var  \UniMapper\Repository */
    private $repository;

    /**
     * Constructor
     *
     * @param \UniMapper\Repository $repository Metadata repository
     */
    function __construct(\UniMapper\Repository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Find file metadata
     *
     * @param \MouseOver\Storage\IStorageFile $file File instance
     *
     * @return mixed
     */
    public function findFor($file)
    {
        $result = $this->repository->find([['fileName', "=", $file->name], ['storageName', "=", $file->storageName]]);

        if (isset($result[0])) {
            return $result[0];
        }

        return false;
    }

    /**
     * Find files metadata
     *
     * @param mixed $where Where conditions
     *
     * @return mixed
     */
    public function findBy($where)
    {
        return $this->repository->find($where);
    }

    /**
     * Remove by file
     *
     * @param \MouseOver\Storage\IStorageFile $file File instance
     *
     * @return mixed
     */
    public function removeFile($file)
    {
        $row = $this->findFor($file);
        $this->repository->delete($row);
        return $row;
    }

    /**
     * Save file metadata
     *
     * @param \MouseOver\Storage\IStorageFile $file     File instance
     * @param array|\Traversable              $metadata Metadata to save
     *
     * @return mixed
     */
    public function saveFileMetadata($file, $metadata = [])
    {
        $row = $this->findFor($file);
        if (!$row) {
            $row = $this->repository->createEntity();
            $row->fileName = $file->name;
            $row->createdTime = new DateTime('now');
        }

        $row->contentType = $file->contentType;
        $row->contentLength = $file->contentLength;
        $row->storageName = $file->storageName;

        foreach ($metadata as $k => $v) {
            $row->{$k} = $v;
        }

        $this->repository->save($row);

        return $row;
    }
}