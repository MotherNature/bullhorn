#!/usr/bin/php
<?php
namespace bullhorn;
use Curl\Curl;

define("KEY_REST_TOKEN", "rest-token");
define("KEY_REST_URL", "rest-url");
define("DEBUG", false);

require __DIR__ . "/../vendor/autoload.php";

if(DEBUG)echo "\n";

if(DEBUG)echo "Refreshing auth data...\n";
passthru(__DIR__ . "/auth.php");

if(!isset($argv)) {
	$argv = [];
}

$url = Data::get(KEY_REST_URL);
$method = strtolower($argv[1]);
$endpoint = $argv[2];

$query = "?";
for($i = 3, $c = count($argv); $i < $c; $i++) {
	$query .= $argv[$i] . "&";
}
$obj = new \StdClass();

$restToken = Data::get(KEY_REST_TOKEN);

if(DEBUG)echo "Performing $method call...\n";

$ch = curl_init();

if($method === "put") {
	for($i = 3, $c = count($argv); $i < $c; $i++) {
		$eq = strpos($argv[$i], "=");
		$key = substr($argv[$i], 0, $eq);
		$value = substr($argv[$i], $eq + 1);
		$obj->$key = $value;
	}

	$query = "";
	$json = json_encode($obj);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($curl->curl, CURLOPT_HTTPHEADER, ["Content-Type: text/plain"]);

	if(DEBUG)echo "\n\n$json\n\n";
}

$startUrl = "$url$endpoint$query";
$endUrl = "BhRestToken=$restToken";

$fullUrl = $startUrl;
if(strstr($startUrl, "?")) {
	$fullUrl = "$startUrl&$endUrl";
}
else {
	$fullUrl = "$startUrl?$endUrl";
}

curl_setopt($ch, CURLOPT_URL, $fullUrl );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

if(DEBUG)echo "\n\n$fullUrl\n\n";
$result = curl_exec($ch);

if(DEBUG)echo "\n\n";
echo $result . "\n";
if(DEBUG)echo "\n";