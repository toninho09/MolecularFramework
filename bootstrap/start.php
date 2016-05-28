<?php

foreach(glob(__DIR__."/../App/Routes/*.php") as $file){
    require $file;
}