<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Tests;

require __DIR__ . '/../bootstrap.php';

use MouseOver\Storage\FileSystem\Storage;
use MouseOver\Tests\TestCase;
use Tester\Assert;
use Tester\Environment;

/**
 * Class StorageTest
 *
 * @package MouseOver\Storage\Tests
 */
class StorageTest extends TestCase
{
    /** @var  \MouseOver\Storage\FileSystem\Storage */
    private $storage;

    /** @var  \MouseOver\Storage\IFileSystem|\Mockery\Mock */
    private $fileSystemMock;

    /** @var  \MouseOver\Storage\IMetadataStorage|\Mockery\Mock */
    private $metadataStorageMock;

    public function setUp()
    {
        Environment::$checkAssertions = false;

        $this->fileSystemMock = \Mockery::mock('\MouseOver\Storage\IFileSystem[create,delete,read,write,copy,isAbsolute,isReadable]');
        $this->metadataStorageMock = \Mockery::mock('\MouseOver\Storage\IMetadataStorage[findFor,findBy,removeFile,saveFileMetadata]');
        $this->storage = new Storage(
            'storage',
            'directory',
            $this->fileSystemMock,
            $this->metadataStorageMock
        );
    }

    public function testGetName()
    {
        Assert::equal('storage',$this->storage->getName());
    }

    public function testGetFilePath()
    {
        Assert::equal('directory/storage/file.txt',$this->storage->filePath('file.txt'));
    }

    public function testGetStorageDir()
    {
        Assert::equal('directory/storage',$this->storage->getStorageDir());
    }

    public function testWriteFile()
    {
        $this->fileSystemMock
            ->shouldReceive('write')
            ->withArgs(['directory/storage/file.txt','foo'])
            ->once();

        $this->fileSystemMock
            ->shouldReceive('isReadable')
            ->once()
            ->withArgs(['directory/storage/file.txt'])
            ->andReturn(true);

        $this->metadataStorageMock->shouldReceive('saveFileMetadata')->once();

        $file = $this->storage->writeFile('file.txt','foo');

        Assert::type('\MouseOver\Storage\IStorageFile', $file);
        Assert::equal('file.txt', $file->name);
        Assert::equal('storage', $file->storageName);
    }

    public function testSaveFileMetadata()
    {
        $this->fileSystemMock
            ->shouldReceive('isReadable')
            ->once()
            ->withArgs(['directory/storage/file.txt'])
            ->andReturn(true);

        $this->metadataStorageMock
            ->shouldReceive('saveFileMetadata')
            ->once();

        $this->storage->saveFileMetadata('file.txt',['foo'=>'foo']);
    }

    public function testGetFileMetadata()
    {
        $this->fileSystemMock
            ->shouldReceive('isReadable')
            ->once()
            ->withArgs(['directory/storage/file.txt'])
            ->andReturn(true);

        $this->metadataStorageMock
            ->shouldReceive('findFor')
            ->once();

        $this->storage->getFileMetadata('file.txt');
    }

    public function testFindFile()
    {
        $this->fileSystemMock
            ->shouldReceive('isReadable')
            ->once()
            ->withArgs(['directory/storage/file.txt'])
            ->andReturn(true);

        $file = $this->storage->findFile('file.txt');

        Assert::type('\MouseOver\Storage\IStorageFile', $file);
        Assert::equal('file.txt', $file->name);
        Assert::equal('storage', $file->storageName);
    }

    public function testFindFiles()
    {
         // TODO
    }

    public function testReadFile()
    {
        $this->fileSystemMock
            ->shouldReceive('read')
            ->once()
            ->withArgs(['directory/storage/file.txt']);
        $this->storage->readFile('file.txt');
    }

    public function testFileExists()
    {
        $this->fileSystemMock
            ->shouldReceive('isReadable')
            ->once()
            ->withArgs(['directory/storage/file.txt']);
        $this->storage->fileExists('file.txt');
    }
}

$testCase = new StorageTest;
$testCase->run();
