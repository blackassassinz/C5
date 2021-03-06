<?php

namespace Concrete\Tests\Config;

use Concrete\Core\Config\FileSaver;
use Illuminate\Filesystem\Filesystem;
use Concrete\Tests\TestCase;

class FileSaverTest extends TestCase
{
    /** @var FileSaver */
    protected $saver;

    /** @var FileSystem */
    protected $files;

    public function setUp():void    {
        $this->saver = new FileSaver($this->files = new Filesystem());
    }

    public function testSavingArray()
    {
        $group = md5(time() . uniqid());

        $this->saver->save('test.array', [1, 2], 'testing', $group);
        $this->saver->save('test.array', [1], 'testing', $group);

        $path = DIR_CONFIG_SITE . "/generated_overrides/{$group}.php";
        $contents = @include_once $path;

        $this->files->delete($path);

        $this->assertEquals([1], array_get($contents, 'test.array'), "Saver doesn't save correctly");
    }

    public function testSavingConfig()
    {
        $group = md5(time() . uniqid());
        $item = 'this.is.the.test.key';
        $value = $group;

        $this->saver->save($item, $value, 'testing', $group);

        $path = DIR_CONFIG_SITE . "/generated_overrides/{$group}.php";
        $exists = $this->files->exists($path);

        $array = [];
        if ($exists) {
            $array = $this->files->getRequire($path);
            $this->files->delete($path);
        }

        $this->assertTrue($exists, 'Failed to save file');
        $this->assertEquals($value, array_get($array, $item), 'Failed to save correct value.');
    }

    public function testSavingNamespacedConfig()
    {
        $group = md5(time() . uniqid());
        $namespace = md5(time() . uniqid());
        $item = 'this.is.the.test.key';
        $value = $group;

        $this->saver->save($item, $value, 'testing', $group, $namespace);

        $path = DIR_CONFIG_SITE . "/generated_overrides/{$namespace}/{$group}.php";
        $exists = $this->files->exists($path);

        $array = [];
        if ($exists) {
            $array = $this->files->getRequire($path);
            $this->files->delete($path);
        }

        $this->assertTrue($exists, 'Failed to save file');
        $this->assertEquals($value, array_get($array, $item), 'Failed to save correct value.');
    }
}
