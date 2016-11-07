<?php

if (!function_exists('breadcrumb')) {
    /**
     * Retrieve the breadcrumb service from the container. Then:
     *
     *  - If it has no parameters, then just return an instance of the service.
     *
     *  - If it has at least two arguments and the second one is a Closure, then
     *    register a new section, with the value of first parameter being the
     *    name of the newly registered section. There is no response value
     *    since it is just a registerer.
     *
     *  - If it has at least one argument and the second (if defined) is not a
     *    Closure, then render the section named as the first parameter and
     *    pass the other parameters as additional info. An array is then
     *    returned with the parsed points.
     *
     * @return \MarcoMdMj\Breadcrumb\Breadcrumb|void|array
     */
    function breadcrumb() {
        $service = app(MarcoMdMj\Breadcrumb\Breadcrumb::class);
        $num_args = func_num_args();

        if ($num_args < 1) {
            return $service;
        }

        $method = ($num_args > 1 and is_callable(func_get_arg(1))) ? 'register' : 'render';

        return call_user_func_array([$service, $method], func_get_args());
    }
}
