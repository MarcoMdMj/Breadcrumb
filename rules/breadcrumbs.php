<?php

/*
|--------------------------------------------------------------------------
| Breadcrumbs
|--------------------------------------------------------------------------
*/

Breadcrumb::register('exampleBreadcrumbHome', function($breadcrumb, $customattr) {
    $breadcrumb->add('Home', '/', [
        'title' => 'This is the home page',
        'attr'  => $customattr
    ]);
});

Breadcrumb::register('exampleBreadcrumbInner', function($breadcrumb) {
    $breadcrumb->import('exampleBreadcrumbHome', 'This is a custom attribute');
    $breadcrumb->add('Inner', '/inner');
});