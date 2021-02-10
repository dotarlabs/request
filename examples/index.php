<?php 

require __DIR__ . "../../vendor/autoload.php";

use Src\Request;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "../../");
$dotenv->load();

/* $customCurl = [
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
]; */

$res = Request::get($_ENV['BASE_API_URL'] . '/todos');
echo json_encode($res);