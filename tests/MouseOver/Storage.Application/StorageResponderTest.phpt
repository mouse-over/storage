<?php

/**
 * This file is part of MouseOver Framework (http://mouse-over.net)
 *
 * @copyright    Copyright (c) 2011-2014 Vaclav Prokes
 */

namespace MouseOver\Storage\Tests;

use MouseOver\Storage\Application\StorageResponder;
use MouseOver\Tests\TestCase;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . '/../bootstrap.php';


 /**
 * Class StorageResponderTest
  *
 * @package MouseOver\Storage\Tests
 */
class StorageResponderTest extends TestCase
{
    /** @var  \MouseOver\Storage\FileSystem\Storage|\Mockery\Mock */
    private $storage;

    /** @var  \MouseOver\Storage\StorageList|\Mockery\Mock */
    private $storageList;

    /** @var \MouseOver\Storage\Application\StorageResponder */
    private $storageResponder;

    public function setUp()
    {
        Environment::$checkAssertions = false;

        $this->storageList = \Mockery::mock('\MouseOver\Storage\StorageList');
        $this->storage = \Mockery::mock('\MouseOver\Storage\IStorage[findFile,fileSize,fileExists]');
        $this->storageResponder = new StorageResponder($this->storageList);
    }

    public function testHandle()
    {
        $this->storageList->shouldReceive('getStorage')->once()->withArgs(['storage'])->andReturn($this->storage);
        $this->storage->shouldReceive('fileExists')->once()->withArgs(['file.txt'])->andReturn(true);
        $this->storage->shouldReceive('findFile')->once()->withArgs(['file.txt'])->andReturn(null);
        $this->storageResponder->handle('storage', 'file.txt');
    }
}

$testCase = new StorageResponderTest;
$testCase->run();