<?
$cgi = (stristr(php_sapi_name(), "cgi") !== false);
$fastCGI = ($cgi && stristr($_SERVER["SERVER_SOFTWARE"], "Microsoft-IIS") !== false);
if ($cgi && !$fastCGI)
	header("Status: 200 OK");
else
	header("HTTP/1.0 200 OK");
echo "SUCCESS";
?>