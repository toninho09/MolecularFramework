<?php

/**
 * @return \Molecular\Framework\Application
 */
function app(){
    return \Molecular\Framework\Application::$instance;
}

/**
 * @return \Molecular\Http\Input
 */
function input(){
    return app()->getRequest()->input();
}

/**
 * @return \Molecular\Container\ServiceContainer
 */
function container(){
    return app()->getContainer();
}

/**
 * @return \Molecular\Injection\Resolve
 */
function resolve(){
    return app()->getInject();
}

/**
 * @param $file
 * @param $date
 * @return \Molecular\View\View
 * @throws Exception
 */
function view($file, $date = null){
    $view = new \Molecular\View\View($file);
    $view->setDefaultViewPath(container()->get('viewDefaultFolder'));
    if (!empty($date)) {
        $view->with($date);
    }
    return $view;
}

/**
 * @return \Molecular\Routes\RouteDispacher
 */
function route(){
    return app()->getRoute();
}
