<?php

namespace App\Traits;

trait Stubable
{
    /**
     * @param array $params = []
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}