<?php

declare(strict_types=1);

namespace App\Enum;

use InvalidArgumentException;
use ReflectionClass;

abstract class Enum
{
    private string $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $reflection = new ReflectionClass(static::class);
        $constants = $reflection->getConstants();

        if (!isset($constants[$name])) {
            throw new InvalidArgumentException("Enum constant '$name' not found in " . static::class);
        }

        return new static($constants[$name]);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $enum): bool
    {
        return $this->value === $enum->value && static::class === $enum::class;
    }
}
