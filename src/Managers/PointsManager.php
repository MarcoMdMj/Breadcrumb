<?php

namespace MarcoMdMj\Breadcrumb\Managers;

use MarcoMdMj\Breadcrumb\Exceptions\BreadcrumbException;

/**
 * Points Manager
 *
 * @package MarcoMdMj\Breadcrumb
 */
class PointsManager
{
    /**
     * Points store.
     *
     * @var array
     */
    private $points = [];

    /**
     * Previous point.
     *
     * @var null
     */
    private $returnTo = null;

    /**
     * Add a new point.
     *
     * @param  string $text
     * @param  string $url
     * @param  array $extra
     * @return PointsManager
     * @throws BreadcrumbException
     */
    public function add($text, $url, array $extra = [])
    {
        if ($this->exists($text)) {
            throw new BreadcrumbException("Point '{$text}' has already been registered.");
        }

        $this->points[$text] = (object) array_merge([
            'text'  => $text,
            'url'   => $url,
            'first' => false,
            'last'  => false
        ], $extra);

        return $this;
    }

    /**
     * Delete all the existing points.
     *
     * @return PointsManager
     */
    public function reset()
    {
        $this->points = [];

        return $this;
    }

    /**
     * Check if the point $text exists.
     *
     * @param  string $text
     * @return bool
     */
    private function exists($text)
    {
        return array_key_exists($text, $this->points);
    }

    /**
     * Parse the existing points.
     *
     * @return array
     */
    public function parse()
    {
        reset($this->points);

        $first = key($this->points);
        $this->points[$first]->first = true;

        end($this->points);

        $last = key($this->points);
        $this->points[$last]->last = true;

        $this->returnTo = prev($this->points);

        reset($this->points);

        return $this->points;
    }

}