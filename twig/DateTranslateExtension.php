<?php
namespace Grav\Plugin;

require_once __DIR__ . '/../vendor/autoload.php';
use Jenssegers\Date\Date;

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
     * @param string $date   DateTime-object or human-readable time declaration
     * @param string $format Date format recognized by PHP
     * @param string $lang   ISO 639-1 language code
     * 
     * @return string
     */
    public function dateTranslate($date, $format, $lang = false)
    {
        if ($lang == false) {
            Date::setLocale($this->grav['language']->getLanguage());
        } else {
            Date::setLocale($lang);
        }
        if (is_string($date)) {
            $parsed = Date::parse($date);
        } else {
            $parsed = new Date($date);
        }
        $formatted = $parsed->format($format);
        return $formatted;
    }
}
