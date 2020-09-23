<?php
require('controller/autoloader.php');
$router = new controller\Router(new controller\Request);
$router->get('/settings', function($request){
    return 'settings';
});
$router->post('/data', function($request){
    return json_encode($request->getBody());
});
?>
