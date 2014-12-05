<?php
/**
 * Translator Component
 */
use Symfony\Component\Translation\Translator as TranslationProvider;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;

class Translator
{
    protected $config;
    protected $translator;
    protected $locale;

    public function __construct()
    {
        $translator_config = require APPDIR . 'config/translation.php';
        $this->config = $translator_config;

        $translator = new TranslationProvider($translator_config['default'], new MessageSelector());
        $translator->setFallbackLocales(array($translator_config['default']));
        $translator->addLoader('array', new PhpFileLoader());
        $this->translator = $translator;

        $app = App::instance();
        $app_locale = $app->cookies()->get('app_locale');
        if ($app_locale) {
            if (in_array($app_locale, $this->config['locales'])) {
                $this->setLocale($app_locale);
            } else {
                $this->setLocale($translator_config['default']);
            }
        } else {
            $this->setLocale($translator_config['default']);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getDefault()
    {
        return $this->getConfig()['default'];
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        $response = new Response();
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie('app_locale', $locale, (time() + 982222)));
        $response->sendHeaders();
    }

    protected function filterVars($vars)
    {
        $filtered = array();
        foreach ($vars as $key => $val) {
            $filtered['{'.$key.'}'] = $val;
        }
        return $filtered;
    }

    public function trans($key = '', $location='', $user_params = array())
    {
        $location = str_replace('.twig', '', $location) . '.php';
        $params = $this->filterVars($user_params);

        $translation_dir = APPDIR . 'translations/';
        if ($this->config['enable'] == false) {
            $this->translator->addResource(
                'array',
                $translation_dir . $this->config['default'] . '/' . $location,
                $this->config['default'],
                $location
            );
            return $this->translator->trans($key, $params, $location, $this->config['default']);
        } else {
            $this->translator->addResource(
                'array',
                $translation_dir . $this->locale . '/' . $location,
                $this->locale,
                $location
            );
            return $this->translator->trans($key, $params, $location, $this->locale);
        }
    }

    public function getTranslator()
    {
        return $this->translator;
    }
}

App::register('translator', new Translator());