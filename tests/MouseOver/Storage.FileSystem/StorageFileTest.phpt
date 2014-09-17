<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Tests;

require __DIR__ . '/../bootstrap.php';

use MouseOver\Tests\TestCase;
use Tester\Assert;
use Tester\Environment;

/**
 * Class StorageFileTest
 *
 * @package MouseOver\Storage\Tests
 */
class StorageFileTest extends TestCase
{
    /** @var  \MouseOver\Storage\FileSystem\Storage|\Mockery\Mock */
    private $storage;

    /** @var  \MouseOver\Storage\FileSystem\StorageFile */
    private $file;

    public function setUp()
    {
        Environment::$checkAssertions = false;

        $this->storage = \Mockery::mock(
            '\MouseOver\Storage\IStorage[getName,filePath,fileSize,fileContentType,readFile,getFileMetadata]'
        );

        $this->file = new \MouseOver\Storage\FileSystem\StorageFile(
            'file.txt',
            $this->storage
        );
    }

    public function testGetName()
    {
        Assert::equal('file.txt', $this->file->getName());
    }

    public function testGetStorageName(){
        $this->storage->shouldReceive('getName')->once()->andReturn('storage');
        $value = $this->file->getStorageName();
        Assert::equal('storage', $value);
    }

    public function testGetRealPath()
    {
        $this->storage->shouldReceive('filePath')->once()->andReturn('path');
        $value = $this->file->getRealPath();
        Assert::equal('path', $value);
    }

    public function testGetContentType()
    {
        $this->storage->shouldReceive('fileContentType')->once()->andReturn('foo');
        $value = $this->file->getContentType();
        Assert::equal('foo', $value);
    }

    public function testGetContentLength()
    {
        $this->storage->shouldReceive('fileSize')->once()->andReturn(10);
        $value = $this->file->getContentLength();
        Assert::equal(10, $value);
    }


    public function testGetContent()
    {
        $this->storage->shouldReceive('readFile')->once()->andReturn('foo');
        $value = $this->file->getContent();
        Assert::equal('foo', $value);
    }

    public function testRead()
    {
        // TODO
    }

    public function testGetMetadata()
    {
        $this->storage->shouldReceive('getFileMetadata')->once()->andReturn(['foo'=>'foo']);
        $value = $this->file->getMetadata();
        Assert::equal(['foo'=>'foo'], $value);
    }

}

$testCase = new StorageFileTest;
$testCase->run();
