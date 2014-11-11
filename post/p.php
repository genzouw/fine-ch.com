<?php
$version = "p.php ver1.3 (2005/03/28)";
$th_count = 10; // 1画面に表示するスレッドの数。
#==================================================
#　リクエスト解析
#==================================================
$url = preg_replace("/(.*)\/post\/.*/", "http://$_SERVER[HTTP_HOST]$1", $_SERVER['SCRIPT_NAME']);
$bbs = '';
$st = 1;
extract($_GET);
// PATH INFOからパラメータを取り出す。
if(!empty($_SERVER['PATH_INFO'])){
	$pairs = explode('/',$_SERVER['PATH_INFO']);
	$bbs = $pairs[1];
	if(!is_dir("../$bbs")) {echo("そんな板ないです。");exit;}
	$st = $pairs[2];
	if (!preg_match("/^\d+$/", $st)) {$st = 0;}
}
if (!is_file("../dat/subject.txt")) {echo("そんな板ないです。");exit;}
$th_titles = file("../dat/subject.txt");
$end = count($th_titles) -1;
if ($st > $end) {$st = $end;}
$mae = $st - $th_count;
if ($mae <= 0) {$mae = 1;}
$tugi = $st + $th_count;
if ($tugi > $end + 1) {$tugi = $end + 1;}

define('VERSION', '2005/04/23');

require __DIR__ . '/init.php';
require __DIR__ . '/functions.php';

loadSetting( $bbs );

// メイン処理
$locale = $bbs;
$localeDirPath	= "../${locale}";

if (!is_dir($localeDirPath)) {
    mkdir($localeDirPath, 777, true);
}

$spIndexFile	= "php://output";

createHtmlIndexForSp($spIndexFile, $subjectfile, $SETTING, $locale, $st);

