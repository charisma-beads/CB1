<?php

declare(strict_types=1);

namespace FileManager\Hydrator\Strategy;

use Laminas\Config\Reader\Ini as IniReader;
use Laminas\Config\Writer\Ini as IniWriter;
use Laminas\Hydrator\Strategy\StrategyInterface;

class ArrayToIniStrategy implements StrategyInterface
{
    public function extract($value): string
    {
        if (is_array($value)) {
            $ini = new IniWriter();
            $ini->setNestSeparator('_');
            $value = $ini->processConfig($value);
        }

        return $value;
    }

    public function hydrate($value): array
    {
        if (is_string($value)) {
            $ini = new IniReader();
            $ini->setNestSeparator('_');
            $value = $ini->fromString($value);
        }

        return $value;
    }
}
