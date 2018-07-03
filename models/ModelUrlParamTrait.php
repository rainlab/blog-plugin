<?php

namespace RainLab\Blog\Models;

/**
 * Trait ModelUrlParamTrait
 *
 * @package RainLab\Blog\Models
 */
trait ModelUrlParamTrait
{
    /**
     * @param string $defaultName
     * @param string[] $params
     *
     * @return string
     */
    protected function getModelUrlParam(string $defaultName, array $params)
    {
        return $params[$defaultName] ?? $defaultName;
    }
}