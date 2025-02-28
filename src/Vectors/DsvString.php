<?php
declare(strict_types=1);

namespace Charcoal\OOP\Vectors;

use Charcoal\OOP\OOP;

/**
 * Class DsvString
 * @package Charcoal\OOP\Vectors
 */
class DsvString extends StringVector
{
    /**
     * @param string|null $value
     * @param int $limit
     * @param bool $caseInsensitive
     * @param string $delimiter
     */
    public function __construct(
        ?string                $value,
        public readonly int    $limit = 0,
        public readonly bool   $caseInsensitive = false,
        public readonly string $delimiter = ",",
    )
    {
        $value = $this->caseInsensitive ? $this->changeCase($value) : strval($value);
        $values = array_map("trim", array_filter(explode($this->delimiter, $value)));
        if ($this->limit > 0) {
            $values = array_slice($values, 0, $this->limit);
        }

        parent::__construct(...$values);
    }

    /**
     * @param string|null $value
     * @return string
     */
    protected function changeCase(?string $value): string
    {
        return strtolower($value ?? "");
    }

    /**
     * @param string|\StringBackedEnum $value
     * @return $this
     */
    public function append(string|\BackedEnum $value): static
    {
        if ($value instanceof \BackedEnum) {
            $value = strval($value->value);
        }

        if ($this->limit > 0 && $this->count >= $this->limit) {
            throw new \OverflowException(static::class . " object has reached its limit of " . $this->limit);
        }

        $value = trim($value);
        if (!$value) {
            throw new \InvalidArgumentException("Value cannot be empty");
        }

        if ($this->caseInsensitive) {
            $value = $this->changeCase($value);
        }

        if (str_contains($value, $this->delimiter)) {
            throw new \InvalidArgumentException(
                sprintf("Value '%s' contains delimiter '%s'", $value, $this->delimiter)
            );
        }

        return parent::append($value);
    }

    /**
     * Create a delimiter separate value string
     * @return string
     */
    public function toString(): string
    {
        return implode($this->delimiter, $this->values);
    }

    /**
     * Checks if vector includes given string
     * @param string|\StringBackedEnum $needle
     * @param bool|null $caseInsensitive
     * @return bool
     */
    public function has(string|\BackedEnum $needle, ?bool $caseInsensitive = null): bool
    {
        if ($needle instanceof \BackedEnum) {
            $needle = strval($needle->value);
        }

        $caseInsensitive = $caseInsensitive ?? $this->caseInsensitive;
        if (!$caseInsensitive) {
            return in_array($needle, $this->values, true);
        }

        return in_array(
            $this->changeCase($needle),
            array_map([$this, "changeCase"], $this->values),
            true
        );
    }

    /**
     * @param string|\BackedEnum $value
     * @param bool|null $caseInsensitive
     * @return bool
     */
    public function delete(string|\BackedEnum $value, ?bool $caseInsensitive = null): bool
    {
        if ($value instanceof \BackedEnum) {
            $value = strval($value->value);
        }

        $caseInsensitive = $caseInsensitive ?? $this->caseInsensitive;
        if ($caseInsensitive) {
            $value = $this->changeCase($value);
        }

        $found = false;
        foreach ($this->values as $index => $existingValue) {
            $compareValue = $this->caseInsensitive ? $this->changeCase($existingValue) : $existingValue;
            if ($compareValue === $value) {
                unset($this->values[$index]);
                $found = true;
            }
        }

        if ($found) {
            $this->values = array_values($this->values);
            $this->count = count($this->values);
        }

        return $found;
    }

    /**
     * @param class-string<\StringBackedEnum> $enumClass
     * @param bool $throw
     * @return $this
     */
    public function enumValidate(string $enumClass, bool $throw = false): static
    {
        $this->filterUnique();

        if (!enum_exists($enumClass)) {
            throw new \LogicException("Enum class does not exist: " . $enumClass);
        }

        $validated = [];
        foreach ($this->values as $value) {
            if (!$enumClass::tryFrom($value)) {
                if (!$throw) {
                    continue;
                }

                throw new \OutOfBoundsException(OOP::baseClassName($enumClass) . " does not define: " . $value);
            }

            $validated[] = $value;
        }

        $this->values = $validated;
        $this->count = count($this->values);
        return $this;
    }

    /**
     * @return $this
     */
    public function filterUnique(): static
    {
        $this->values = array_values(array_unique($this->values, SORT_STRING));
        $this->count = count($this->values);
        return $this;
    }
}