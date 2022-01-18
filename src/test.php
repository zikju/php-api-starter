<?php
use zikju\Core\Response;

$response = new Response(
    'ok',
    'Hooray! Everything is working!'
);
$response->print();
die();
