<?php

namespace Grav\Plugin;

use Carbon\Carbon;

/**
 * Localized date translation via Twig
 */
class DateTranslateExtension extends \Twig_Extension
{
    /**
     * Initialize class
     *
     * @param \Grav $grav Instance of Grav
     */
    public function __construct($grav)
    {
        $this->grav = $grav;
    }

    /**
     * Declare Extension-name
     *
     * @return string
     */
    public function getName()
    {
        return 'DateTranslate';
    }

    /**
     * Declare functions
     *
     * @return \Twig_SimpleFunction
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dt', [$this, 'dateTranslate']),
        );
    }

    /**
     * Translate a date with localization
     *
     * @param string $date   DateTime-object or human-readable time declaration.
     * @param string $format Date format recognized by PHP.
     * @param string $lang   ISO 639-2 or ISO 639-3 language code.
     *
     * @return string Formatted date.
     */
    public function dateTranslate($date, $format, $lang = false)
    {
        if (!empty($lang)) {
            $locale = $lang;
        } elseif (!empty($this->grav['language']->getLanguage())) {
            $locale = $this->grav['language']->getLanguage();
        } else {
            $locale = Grav::instance()['config']->get('plugins.timeline.locale', 'en');
        }
        return Carbon::parse($date)->locale($locale)->format($format);
    }
}
