<?php
namespace Payment;

class PaymentUtils
{
    public static function getValue($map, $key, $default = null, $acceptWhiteSpace = true)
    {
        return isset($map[$key]) && ($acceptWhiteSpace || $map[$key]!=='') ? $map[$key] : $default;
    }

    public static function appendParameter($url, $param)
    {
        return strpos($url, '?') ? $url.'&'.$param : $url.'?'.$param;
    }

    public static function normalizeLanguage($language)
    {
        return substr($language, 0, 2);
    }

    public static function getGlobalLanguage()
    {
        try {
            $const = constant('AXEPTA_LANG');
            if (constant('AXEPTA_LANG') !== null && !empty($const)) {
                $const = self::normalizeLanguage($const);
                return strtolower($const);
            }
        } catch (\Exception $e) {

        }

        return 'en';
    }

    public static function getLabelText($label)
    {
        $filePath = __DIR__ . "/Data/labels.xml";
        $cLang = self::getGlobalLanguage();

        if (file_exists($filePath)) {
            $xmlElements = simplexml_load_file($filePath);
            $query = "//label[code='".$label."']";
            $xmlElements = $xmlElements->xpath($query);

            return (string)$xmlElements[0]->{'text_' . $cLang};
        }
        return "";
    }
}
