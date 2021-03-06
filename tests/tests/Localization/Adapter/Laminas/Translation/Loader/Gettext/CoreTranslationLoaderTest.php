<?php

namespace Concrete\Tests\Localization\Adapter\Laminas\Translation\Loader\Gettext;

use Concrete\Core\Localization\Translator\Adapter\Laminas\Translation\Loader\Gettext\CoreTranslationLoader;
use Concrete\Core\Localization\Translator\Adapter\Laminas\TranslatorAdapterFactory;
use Concrete\Core\Support\Facade\Facade;
use Concrete\TestHelpers\Localization\LocalizationTestsBase;
use Illuminate\Filesystem\Filesystem;

/**
 * Tests for:
 * Concrete\Core\Localization\Translator\Adapter\Laminas\Translation\Loader\Gettext\CoreTranslationLoader.
 *
 * @author Antti Hukkanen <antti.hukkanen@mainiotech.fi>
 */
class CoreTranslationLoaderTest extends LocalizationTestsBase
{
    protected $adapter;
    protected $loader;
    private static $languagesDirectoryExisted = false;

    public static function setUpBeforeClass():void
    {
        parent::setUpBeforeClass();
        $filesystem = new Filesystem();
        $langDir = DIR_TESTS . '/assets/Localization/Adapter/Laminas/Translation/Loader/Gettext/languages/fi_FI';
        $appLangDir = parent::getTranslationsFolder() . '/fi_FI';
        $filesystem->copyDirectory($langDir, $appLangDir);
    }

    public function setUp():void
    {
        $factory = new TranslatorAdapterFactory();
        $this->adapter = $factory->createTranslatorAdapter('fi_FI');

        $app = Facade::getFacadeApplication();
        $this->loader = new CoreTranslationLoader($app);
    }

    public function testLoadTranslations()
    {
        $this->loader->loadTranslations($this->adapter);

        $this->assertEquals('Tervehdys ytimestä!', $this->adapter->translate('Hello from core!'));
    }
}
