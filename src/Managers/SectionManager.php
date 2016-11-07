<?php

namespace MarcoMdMj\Breadcrumb\Managers;

use Closure;
use MarcoMdMj\Breadcrumb\Exceptions\BreadcrumbException;

/**
 * Section manager
 *
 * @package MarcoMdMj\Breadcrumb\Managers
 */
class SectionManager
{
    /**
     * Sections store.
     *
     * @var array
     */
    private $sections = [];

    /**
     * True if a custom breadcrumb has been initialized.
     *
     * @var bool
     */
    private $customSection = false;

    /**
     * Add a new callable section.
     *
     * @param  string $name
     * @param  Closure $callback
     * @return SectionManager
     * @throws BreadcrumbException
     */
    public function add($name, Closure $callback)
    {
        if ($this->exists($name)) {
            throw new BreadcrumbException("Section '{$name}' has already been registered.");
        }

        $this->sections[$name] = $callback;

        return $this;
    }

    /**
     * Return the callback of the requested section.
     *
     * @param  string $name
     * @return Closure
     * @throws BreadcrumbException
     */
    public function load($name)
    {
        if (!$this->exists($name)) {
            throw new BreadcrumbException("Section '{$name}' has not been registered.");
        }

        return $this->sections[$name];
    }

    /**
     * Check is the section $name has been registered.
     *
     * @param  string $name
     * @return bool
     */
    private function exists($name)
    {
        return array_key_exists($name, $this->sections);
    }

    /**
     * Check if a custom breadcrumb has been initialized, or start one.
     *
     * @param  bool|null $value
     * @return bool
     */
    public function custom($value = null)
    {
        if (!is_null($value)) {
            return $this->customSection = $value;
        }

        return $this->customSection;
    }
}