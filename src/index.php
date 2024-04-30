<?
require_once(__DIR__."/vendor/autoload.php");
require_once("rss.php") ;

date_default_timezone_set('Asia/Tokyo');

$rss = new Rss(__DIR__."/conf.d/config.yaml");

print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$rss->disp();
?>