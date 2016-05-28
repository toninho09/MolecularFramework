<?php
route()->get("/",function(){
   return 'is alive =)';
});

route()->get('/index','\App\Controller\HomeController@index');
