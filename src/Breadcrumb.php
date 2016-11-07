<?php

namespace MarcoMdMj\Breadcrumb;

use Closure;

/**
 * Breadcrumb main service.
 *
 * @package MarcoMdMj\Breadcrumb
 */
class Breadcrumb
{
    /**
     * An instance of the breadcrumb builder.
     *
     * @var Builder
     */
    private $builder;

    /**
     * Initialize the service and store the builder instance.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Register a new callable section.
     *
     * @param  string  $name
     * @param  Closure $callback
     * @return void
     * @throws Exceptions\BreadcrumbException
     */
    public function register($name, Closure $callback)
    {
        $this->builder->register($name, $callback);
    }

    /**
     * Return the parsed breadcrumb. If $section is null, then assume there
     * are custom points previously defined and use them to generate the
     * response. Otherwise, find the section named $section and parse
     * its closure. In any case, the response is an array with the
     * parsed points.
     *
     * @param  null $section
     * @return array
     * @throws Exceptions\BreadcrumbException
     */
    public function render($section = null)
    {
        if (is_null($section)) {
            return $this->builder->customMake();
        }

        $params = array_slice(func_get_args(), 1);

        return $this->builder->make($section, $params);
    }

    /**
     * Add a custom point to the builder.
     *
     * @param  string $text
     * @param  string $url
     * @param  array $args
     * @return void
     * @throws Exceptions\BreadcrumbException
     */
    public function point($text, $url, array $args = [])
    {
        $this->builder->customPoint($text, $url, $args);
    }
}