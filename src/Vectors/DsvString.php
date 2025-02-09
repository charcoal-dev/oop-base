<?php
declare(strict_types=1);

namespace Charcoal\OOP\Vectors;

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
        public readonly string $delimiter = ","
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
     * @param string $value
     * @return $this
     */
    public function append(string $value): static
    {
        if ($this->limit > 0 && $this->count >= $this->limit) {
            throw new \OverflowException(static::class . " object has reached its limit of " . $this->limit);
        }

        $value = trim($value);
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
     * @return array
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * Checks if vector includes given string
     * @param string $needle
     * @param bool|null $caseInsensitive
     * @return bool
     */
    public function has(string $needle, ?bool $caseInsensitive = null): bool
    {
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
}