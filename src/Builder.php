<?php

namespace MarcoMdMj\Breadcrumb;

use Closure;
use MarcoMdMj\Breadcrumb\Managers\PointsManager;
use MarcoMdMj\Breadcrumb\Managers\SectionManager;
use MarcoMdMj\Breadcrumb\Exceptions\BreadcrumbException;

/**
 * Breadcrumb builder engine.
 *
 * @package MarcoMdMj\Breadcrumb
 */
class Builder
{
    /**
     * Instance of the SectionManager.
     *
     * @var SectionManager
     */
    private $section;

    /**
     * Instance of the PointsManager.
     *
     * @var PointsManager
     */
    private $point;

    /**
     * Initialize the builder and its dependencies.
     *
     * @param SectionManager $section
     * @param PointsManager $point
     */
    public function __construct(SectionManager $section, PointsManager $point)
    {
        $this->section = $section;
        $this->point   = $point;
    }

    /**
     * Register a callable section.
     *
     * @param  string  $section
     * @param  Closure $callback
     * @return $this
     * @throws BreadcrumbException
     */
    public function register($section, Closure $callback)
    {
        return $this->section->add($section, $callback);
    }

    /**
     * Parse the $section callback to generate the breadcrumb.
     *
     * @param  string $section
     * @param  array $params
     * @return array
     * @throws BreadcrumbException
     */
    public function make($section, array $params = [])
    {
        $this->point->reset();

        try {
            $this->call($section, $params);
        } catch (\ErrorException $exception) {
            throw new BreadcrumbException("The section '$section' could not be parsed: " . $exception->getMessage());
        }

        return $this->point->parse();
    }

    /**
     * Within a section closure, import another section.
     *
     * @param  string $section
     * @return Builder
     * @throws BreadcrumbException
     */
    public function import($section)
    {
        $params = array_slice(func_get_args(), 1);

        $this->call($section, $params);

        return $this;
    }

    /**
     * Within a section closure, add a point.
     *
     * @param  string $text
     * @param  string $url
     * @param  array  $args
     * @return Builder
     * @throws BreadcrumbException
     */
    public function add($text, $url, array $args = [])
    {
        $this->point->add($text, $url, $args);

        return $this;
    }

    /**
     * Execute the closure of the $section, passing an instance of
     * this Builder.
     *
     * @param  string $section
     * @param  array  $params
     * @return void
     * @throws BreadcrumbException
     */
    private function call($section, array $params = [])
    {
        $callback = $this->section->load($section);

        array_unshift($params, $this);

        call_user_func_array($callback, $params);
    }

    /**
     * Add a custom point.
     *
     * @param  string $name
     * @param  string $url
     * @param  array  $args
     * @return void
     * @throws BreadcrumbException
     */
    public function customPoint($name, $url, array $args = [])
    {
        if (!$this->section->custom()) {
            $this->section->custom(true);
            $this->point->reset();
        }

        $this->point->add($name, $url, $args);
    }

    /**
     * Parse the previously ade
     *
     * @return array
     * @throws BreadcrumbException
     */
    public function customMake()
    {
        if (!$this->section->custom()) {
            throw new BreadcrumbException('No points were added to the builder.');
        }

        return $this->point->parse();
    }
}