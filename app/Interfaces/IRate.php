<?php
declare(strict_types=1);

namespace App\Interfaces;

interface IRate
{
    /**
     * @return bool
     */
    public function isConvertible(): bool;

    /**
     * @return float|null
     */
    public function getUsd(): ?float;
}
