<?php

namespace MouseOver\Storage\FileSystem;

use MouseOver\Storage\IReader;
use Nette\InvalidStateException;


/**
 * Class FileReader
 * @package MouseOver\Storage\FileSystem
 */
class FileReader implements IReader
{

    /** @var  resource|bool File handle */
    private $handle;

    /** @var  int */
    private $fileSize;

    /** @var  int */
    private $length;

    /** @var  int */
    private $currentLength;

    /** @var  int|null */
    private $rangeStart;

    /** @var  int|null */
    private $rangeEnd;


    /**
     * Constructor
     *
     * @param string $filePath File path
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($filePath)
    {
        $this->fileSize = $this->length = $this->currentLength = filesize($filePath);
        $this->handle = fopen($filePath, 'r');
        if ($this->handle === false) {
            throw new \InvalidArgumentException('File not readable or not exists!', 0);
        }
    }

    /**
     * Set read range
     *
     * @param int      $rangeStart Range start
     * @param int|null $rangeEnd   Range end
     *
     * @throws \Nette\InvalidStateException
     * @throws \InvalidArgumentException
     * @return void
     */
    public function setRange($rangeStart, $rangeEnd = null)
    {
        if ($this->length !== $this->currentLength) {
            throw new InvalidStateException('Can\'t set, already reading!');
        }
        if ($rangeStart !== null || $rangeEnd !== null) {
            if ($rangeStart === null) {
                $this->rangeStart = max(0, $this->fileSize - $rangeEnd);
                $this->rangeEnd = $this->fileSize - 1;
            } else if ($rangeEnd === null || $rangeEnd > $this->fileSize - 1) {
                $this->rangeEnd = $this->fileSize - 1;
            }

            if ($this->rangeEnd < $this->rangeStart) {
                throw new \InvalidArgumentException('Requested range not satisfiable!', 416);
            }

            fseek($this->handle, $this->rangeStart);
        }
    }

    /**
     * Return's range end
     *
     * @return int|null
     */
    public function getRangeEnd()
    {
        return $this->rangeEnd;
    }

    /**
     * Return's range start
     *
     * @return int|null
     */
    public function getRangeStart()
    {
        return $this->rangeStart;
    }

    /**
     * File size
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Current read length
     *
     * @return int
     */
    public function getCurrentLength()
    {
        return $this->currentLength;
    }

    /**
     * Content length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Read
     *
     * @return bool|string
     */
    public function read()
    {
        if ($this->handle) {
            if (feof($this->handle)) {
                $this->currentLength = 0;
            }
            if ($this->currentLength > 0) {
                $s = fread($this->handle, min(4e6, $this->currentLength));
                $this->currentLength -= strlen($s);
                return $s;
            } else {
                fclose($this->handle);
                $this->handle = null;
                return false;
            }
        }
        return false;
    }

    /**
     * Can read
     *
     * @return boolean
     */
    public function hasContent()
    {
        return $this->currentLength > 0;
    }


    /**
     * Destructor
     */
    function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }


}