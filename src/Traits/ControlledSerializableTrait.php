<?php
declare(strict_types=1);

namespace Charcoal\OOP\Traits;

/**
 * Trait ControlledSerializableTrait
 * @package Charcoal\OOP\Traits
 */
trait ControlledSerializableTrait
{
    abstract protected function collectSerializableData(): array;

    abstract protected function onUnserialize(array $data): void;

    final public function __serialize(): array
    {
        return $this->collectSerializableData();
    }

    final public function __unserialize(array $data): void
    {
        $this->onUnserialize($data);
    }
}