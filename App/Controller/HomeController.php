<?php
namespace App\Controller;
use App\Model\HomeModel;

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/05/16
 * Time: 03:28
 */
class HomeController
{
    public function index(){
        $model = new HomeModel();
        $model->work = "it's Work";
        return  view('home.php',['model'=>$model]);
    }

}