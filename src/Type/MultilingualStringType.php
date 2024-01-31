<?php


namespace Keym\MultilingualString\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Keym\MultilingualString\MultilingualString;

class MultilingualStringType extends JsonType
{

    public function getName() : string
    {
        return 'multilingual';
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return string|null
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $translations = array_filter($value->extract());

        return parent::convertToDatabaseValue($translations, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) : MultilingualString
    {
        if(!$value) $value = "[]";

        $value = new MultilingualString(json_decode($value, true));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $value;
    }

}
