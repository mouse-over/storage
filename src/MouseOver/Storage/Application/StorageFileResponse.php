<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright	Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Application;

use MouseOver;
use Nette;
use Nette\Utils\Strings;

/**
 * Class StorageFileResponse
 * @package MouseOver\Storage
 */
class StorageFileResponse implements Nette\Application\IResponse
{
    use \Nette\SmartObject;

    /** @var \MouseOver\Storage\IStorageFile */
    private $storageFile;

    /**
     * Constructor
     *
     * @param \MouseOver\Storage\IStorageFile $storageFile Storage file
     */
    public function __construct($storageFile)
    {
        $this->storageFile = $storageFile;
    }

    /**
     * Sends response to output.
     *
     * @param \Nette\Http\IRequest  $httpRequest  HTTP request
     * @param \Nette\Http\IResponse $httpResponse HTTP response
     *
     * @throws \Nette\Application\BadRequestException
     * @return void
     */
    public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
    {
        $httpResponse->setContentType($this->storageFile->contentType ?: 'application/octet-stream');
        $httpResponse->setHeader('Content-Disposition',
            ($this->storageFile->isForceDownload() ? 'attachment' : 'inline')
            . '; filename="' . $this->storageFile->publicName . '"'
            . '; filename*=utf-8\'\'' . rawurlencode($this->storageFile->publicName));

        $this->sendStorageFile($this->storageFile, $httpRequest, $httpResponse);
    }

    /**
     * Sends chunked response to output.
     *
     * @param \MouseOver\Storage\IStorageFile $storageFile  Storage file
     * @param \Nette\Http\IRequest            $httpRequest  HTTP request
     * @param \Nette\Http\IResponse           $httpResponse HTTP response
     *
     * @return void
     */
    protected function sendStorageFile(
        $storageFile,
        Nette\Http\IRequest $httpRequest,
        Nette\Http\IResponse $httpResponse
    ) {
        $httpResponse->setHeader('Accept-Ranges', 'bytes');

        $reader = $storageFile->getReader();

        if (preg_match('#^bytes=(\d*)-(\d*)\z#', $httpRequest->getHeader('Range'), $matches)) {
            list(, $start, $end) = $matches;
            if ($start === '') {
                $start = null;

            }
            if ($end === '') {
                $end = null;
            }

            try {
                $reader->setRange($start, $end);
            } catch (\InvalidArgumentException $invalidArgumentException) {
                $httpResponse->setCode(416); // requested range not satisfiable
                return;
            }

            $httpResponse->setCode(206);
            $httpResponse->setHeader(
                'Content-Range',
                'bytes ' . $reader->getRangeStart() . '-' . $reader->getRangeEnd() . '/' . $reader->getFileSize()
            );
            $reader->setRange($start, $end);
            $httpResponse->setHeader('Content-Length', $reader->getLength());

        } else {
            $httpResponse->setHeader(
                'Content-Range',
                'bytes 0-' . ($reader->getFileSize() - 1) . '/' . $reader->getFileSize()
            );
            $httpResponse->setHeader('Content-Length', $reader->getLength());
        }

        while ($reader->hasContent()) {
            echo $reader->read();
        };

    }
}

