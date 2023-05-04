<?php

namespace Reedware\Icons\Contracts;

interface Manager
{
    /**
     * Returns the html for the specified icon.
     */
    public function icon(string $name): string;
}
