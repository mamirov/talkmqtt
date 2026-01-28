<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Util;

use ReflectionClass;

class JsonUtil
{

    public static function serializeJson($class, $payload)
    {
        if (is_array($class)) {
            foreach ($class as $arrayEl) {
                $payload[] = self::serializeJson($arrayEl, $payload);
            }
        }
        $methods = get_class_methods($class);
        $reflectionClass = new ReflectionClass($class::class);
        foreach ($methods as $method) {
            if ($method == 'getFieldTypes') {
                foreach ($class->getFieldTypes() as $fieldType => $value) {
                    $payload[$fieldType] = $class->{'get' . ucfirst($fieldType)}();
                }
                continue;
            }
            if (str_starts_with($method, 'get') || str_starts_with($method, 'is')) {
                $fieldName = str_starts_with($method, 'get') ? lcfirst(substr($method, 3)) : $method;
                $refMethod = $reflectionClass->getMethod($method);
                if ($refMethod->getNumberOfParameters() > 0) {
                    continue;
                }
                $fieldValue = $class->$method();
                if (is_object($fieldValue) || (is_array($fieldValue) && !empty($fieldValue) && is_object($fieldValue[0]))) {
                    $payload[$fieldName] = [];
                    $payload[$fieldName] = self::serializeJson($fieldValue, $payload[$fieldName]);
                } else {
                    $payload[$fieldName] = $fieldValue;
                }
            }
        }
        return $payload;
    }
}
