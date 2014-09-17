<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */


namespace MouseOver\Storage;


/**
 * Interface IMetadataStorage
 *
 * @package MouseOver\Storage
 */
interface IMetadataStorage
{

    /**
     * Find file metadata
     *
     * @param \MouseOver\Storage\IStorageFile $file File instance
     *
     * @return mixed
     */
    public function findFor($file);

    /**
     * Find files metadata
     *
     * @param mixed $where Where conditions
     *
     * @return mixed
     */
    public function findBy($where);

    /**
     * Remove by file
     *
     * @param \MouseOver\Storage\IStorageFile $file File instance
     *
     * @return mixed
     */
    public function removeFile($file);

    /**
     * Save file metadata
     *
     * @param \MouseOver\Storage\IStorageFile $file     File instance
     * @param array|\Traversable              $metadata Metadata to save
     *
     * @return mixed
     */
    public function saveFileMetadata($file, $metadata = []);
}