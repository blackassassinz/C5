<?php
namespace Concrete\Core\Localization\Translator\Adapter\Core;

use Concrete\Core\Application\Application;
use Concrete\Core\Config\Repository\Repository as Config;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Localization\Translator\Adapter\Plain\TranslatorAdapterFactory as PlainTranslatorAdapterFactory;
use Concrete\Core\Localization\Translator\Adapter\Laminas\TranslatorAdapterFactory as LaminasTranslatorAdapterFactory;
use Concrete\Core\Localization\Translator\TranslatorAdapterFactoryInterface;

/**
 * The core translator adapter factory is a wrapper factory that abstracts the
 * translator creation based on the passed locale. By default, a translator
 * instance will be created through the {@link LaminasTranslatorAdapterFactory}.
 *
 * If the passed locale is the system's base locale and if translations are
 * NOT enabled for the base locale, a translator instance will be created
 * through the {@link PlainTranslatorAdapterFactory}.
 *
 * @author Antti Hukkanen <antti.hukkanen@mainiotech.fi>
 */
class TranslatorAdapterFactory implements TranslatorAdapterFactoryInterface
{
    /** @var Config */
    protected $config;
    /** @var PlainTranslatorAdapterFactory */
    protected $plainFactory;
    /** @var LaminasTranslatorAdapterFactory */
    protected $laminasFactory;

    /**
     * @param Config $config
     * @param Application $app
     * @param array $settings
     */
    public function __construct(Config $config, PlainTranslatorAdapterFactory $plainFactory, LaminasTranslatorAdapterFactory $laminasFactory)
    {
        $this->config = $config;
        $this->plainFactory = $plainFactory;
        $this->laminasFactory = $laminasFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createTranslatorAdapter($locale)
    {
        if ($locale == Localization::BASE_LOCALE &&
            !$this->shouldTranslateBaseLocale()
        ) {
            return $this->plainFactory->createTranslatorAdapter($locale);
        } else {
            return $this->laminasFactory->createTranslatorAdapter($locale);
        }
    }

    private function shouldTranslateBaseLocale()
    {
        // Backwards compatibility
        if ($this->config->get('concrete.misc.enable_translate_locale_en_us')) {
            return true;
        }

        return $this->config->get('concrete.misc.enable_translate_locale_base_locale');
    }
}
