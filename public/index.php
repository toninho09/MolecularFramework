<?php

require __DIR__.'/../vendor/autoload.php';

$app = new \Molecular\Framework\Application();
$app->getContainer()->set('viewDefaultFolder',__DIR__."/../App/View/");

require __DIR__.'/../bootstrap/global.php';

require __DIR__.'/../bootstrap/start.php';

$app->run();
echo $app->getResponseContent();
