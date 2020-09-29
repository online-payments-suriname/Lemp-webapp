<?php
namespace controller;

class Router{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST",
        "LOAD"
    );

    //depends on the Request object;
    function __construct(IRequest $request){
        $this->request = $request;
    }

    // $name is the inaccessible method name, $args is the arguments of the method
    function __call($name, $args){
        //create associative array tha maps routes to callbacks
        list($route, $method) = $args;

        if(!in_array(strtoupper($name), $this->supportedHttpMethods)){
            echo $name;
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /** removes tralling forward slashes from the end of the route
     * @param route (string)
     */
    private function formatRoute($route){
        $result = rtrim($route, '/');
        if($result === ''){
            return '/';
        }
        $result = $this->getParams($result);
        return $result;
    }

    private function invalidMethodHandler(){
        header("{$this->request->serverProtocol} 405 Method not Allowed");
    }

    private function defaultRequestHandler(){
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    function getParams($route){
        $route=ltrim($route,'/');
        $route=explode('/',$route);
        foreach($route as $key => $value){
            if($key<=1)continue;
            $this->request->{$this->params[$key]}=$route[$key];
        }
        return '/'.$route[0].'/'.$route[1];
    }

    static function redirect($url){
        header('Location:'.$url);
        exit();
    }
    /*
     * resolves a route
     */
    function resolve(){
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formatedRoute];

        if(is_null($method)){
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    function __destruct(){
        $this->resolve();
    }
}
