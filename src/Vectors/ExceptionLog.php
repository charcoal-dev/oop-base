<?php
declare(strict_types=1);

namespace Charcoal\OOP\Vectors;

/**
 * Class ExceptionLog
 * @package Charcoal\OOP\Vectors
 */
class ExceptionLog extends AbstractVector
{
    /**
     * @param \Throwable|null $error
     */
    public function __construct(?\Throwable $error = null)
    {
        parent::__construct($error ? [$error] : []);
    }

    /**
     * @param \Throwable $error
     * @return $this
     */
    final public function append(\Throwable $error): static
    {
        $this->values[] = $error;
        $this->count++;
        return $this;
    }
}