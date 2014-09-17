<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage;


interface IReader {

    /**
     * Set read range
     *
     * @param int $rangeStart
     * @param int|null $rangeEnd
     *
     * @return void
     */
    public function setRange($rangeStart, $rangeEnd = null);

    /**
     * Return's range end
     *
     * @return int|null
     */
    public function getRangeEnd();

    /**
     * Return's range start
     *
     * @return int|null
     */
    public function getRangeStart();

    /**
     * File size
     *
     * @return int
     */
    public function getFileSize();

    /**
     * Content length
     *
     * @return int
     */
    public function getLength();

    /**
     * Read content
     *
     * @return string
     */
    public function read();

    /**
     * Can read
     *
     * @return boolean
     */
    public function hasContent();

}