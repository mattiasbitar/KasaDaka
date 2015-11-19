<?php
error_reporting ( E_ALL );
include("config.php");
include("ttslib.php");
if(!defined('DEBUG'))
 error_reporting(0);
debug("Mode debug enabled");
if(isset($_POST["text"])){
 $text = $_POST["text"];
 if(isset($_POST["format"])) $format = $_POST["format"];
 debug(" POST VARIABLES :");
 debug(print_r( $_POST,true));
}elseif(isset($_GET["text"])){
 $text = $_GET["text"];
 if(isset($_GET["format"])) $format = $_GET["format"];
 debug(" GET VARIABLES :");
 debug(print_r( $_GET,true));
}else
 httperror();
debug(" text = $text");
debug(" format = $format");
switch($format){
 case 'gif':
 headerdebug("Content-Type: image/gif");
 headerdebug('Content-Disposition: attachment; filename="file.gif"');
 break;
 case 'wav':
 default :
 headerdebug("Content-Type: audio/wav");
 headerdebug('Content-Disposition: attachment; filename="file.wav"');
}
$filename=tempnam("/tmp",$ttsname."TMP");
$file=fopen($filename, "w");
debug(" Temp file = $filename");
register_shutdown_function('cleanup',"$filename");
fwrite($file, $text);
fwrite($file, "\n");
fclose($file);
$program = "/usr/bin/flite";
$time_start = microtime(true);
register_shutdown_function('cleanup',"$filename.wav");
exec($program." -f $filename -o $filename.wav", $return, $status);
$time_end = microtime(true);
$texec = $time_end - $time_start;
$outsize=filesize("$filename.wav");
readfile("$filename.wav");
if($enable_record_to_cdr)
register_shutdown_function('cdrrecord',$ttsname,$lang,$status,$texec,$text,
$outsize);
register_shutdown_function('garbage',$ttsname);
// vim: set filetype=php expandtab tabstop=2 shiftwidth=2 autoindent
smartindent:
?>

