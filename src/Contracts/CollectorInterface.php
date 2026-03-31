<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc\Contracts;

interface CollectorInterface
{
    public function collect(): array;
}