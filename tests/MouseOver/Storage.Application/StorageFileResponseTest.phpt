<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Tests;

use MouseOver\Tests\TestCase;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../bootstrap.php';


 /**
 * Class StorageFileResponse
  *
 * @package MouseOver\Tests
 */
class StorageFileResponseTest extends TestCase
{
    /** @var  \MouseOver\Storage\IStorageFile|\Mockery\Mock */
    private $file;

    public function setUp()
    {
        Environment::$checkAssertions = false;

        $this->file = \Mockery::mock('\MouseOver\Storage\IStorageFile');
    }

    public function testSend()
    {

        $origData = file_get_contents(__FILE__);

        $this->file->contentType = 'foo';
        $this->file->name = 'foo';
        $this->file->content = $origData;
        $this->file->contentLength = filesize(__FILE__);

        $readerMock = \Mockery::mock('\MouseOver\Storage\IReader');
        $readerMock->shouldReceive('getFileSize')->once()->andReturn($this->file->contentLength);
        $readerMock->shouldReceive('getLength')->once()->andReturn($this->file->contentLength);
        $readerMock->shouldReceive('read')->once()->andReturn($origData);
        $readerMock->shouldReceive('hasContent')->twice()->andReturn(true, false)->ordered();

        $this->file->shouldReceive('getReader')->once()->andReturn($readerMock);

        $fileResponse = new \MouseOver\Storage\Application\StorageFileResponse($this->file);

        ob_start();
        $fileResponse->send(new \Nette\Http\Request(new \Nette\Http\UrlScript), new \Nette\Http\Response);
        Assert::same( $origData, ob_get_clean() );
    }

    public function testSendChunked()
    {

    }
}

$testCase = new StorageFileResponseTest;
$testCase->run();