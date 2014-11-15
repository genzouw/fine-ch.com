<?php
$thread=20;
require("passcheck.php");
$comment = '<br>';
#====================================================
#　初期情報の取得（設定ファイル）
#====================================================
#設定ファイルを読む
$bbs = isset($_REQUEST['bbs']) ? $_REQUEST['bbs'] : 'jp';

$set_pass = "../${bbs}/SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file ($set_pass);
	foreach ($set_str as $tmp){
		$tmp = chop($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("ＥＲＲＯＲ！","ＥＲＲＯＲ：ユーザー設定が消失しています！");
$threadconf = array();
$fp = fopen("../${bbs}/cgi/threadconf.cgi", "r");
while ($list = fgetcsv($fp, 1024)) {
	$threadconf[$list[0]] = $list;
}
#==================================================
#　ファイル操作（サブジェクトファイル読み込み）
#==================================================
#サブジェクトファイルを読み込む
$subfile = "../dat/subject.txt";
#サブジェクトファイルを読み込む
$SUBJECTLIST = @file($subfile);
#サブジェクト内容をハッシュに格納
$PAGEFILE = array();
if ($SUBJECTLIST) {
	foreach ($SUBJECTLIST as $tmp) {
		$tmp = rtrim($tmp);
		list($file, $value) = explode("<>", $tmp);
		$filename = "../dat/$file";
		if (is_file($filename)) {
			#datが存在する場合のみ最後に追加
			preg_match("/(\d+)/", $file, $match);
			$file = $match[1];
			array_push($PAGEFILE,$file);
			$SUBJECT[$file] = $value;
		}
	}
}
#==================================================
#　設定変更
#==================================================
if (isset($_POST['mode']) and $_POST['mode'] == "set") {
	$target = "../dat/$_POST[key].dat";
	if (!is_file($target)) disperror("ＥＲＲＯＲ！", "そんな板orスレッドないです。");
	if (!isset($_POST['name_774'])) $_POST['name_774'] = '';
	if (!isset($_POST['force_774'])) $_POST['force_774'] = '';
	if (!isset($_POST['no_id'])) $_POST['no_id'] = 0;
	if (!isset($_POST['sage'])) $_POST['sage'] = 0;
	if (!isset($_POST['stars'])) $_POST['stars'] = 0;
	if (!isset($_POST['normal'])) $_POST['normal'] = 0;
	if (!isset($_POST['name'])) $_POST['name'] = 0;
	if (!isset($_POST['zerothello'])) $_POST['zerothello'] = 0;
	if (!isset($_POST['up'])) $_POST['up'] = 0;
	if (!isset($_POST['maxmsg'])) $_POST['maxmsg'] = '';
	$threadconf[$_POST['key']][0] = $_POST['key'];
	$threadconf[$_POST['key']][1] = $_POST['name_774'];
	$threadconf[$_POST['key']][2] = $_POST['force_774'];
	$threadconf[$_POST['key']][3] = $_POST['no_id'];
	$threadconf[$_POST['key']][4] = $_POST['sage'];
	$threadconf[$_POST['key']][5] = $_POST['stars'];
	$threadconf[$_POST['key']][6] = $_POST['normal'];
	$threadconf[$_POST['key']][7] = $_POST['name'];
	$threadconf[$_POST['key']][8] = $_POST['zerothello'];
	$threadconf[$_POST['key']][9] = $_POST['up'];
	$threadconf[$_POST['key']][10] = $_POST['maxmsg'];
	$fp = fopen("../${bbs}/cgi/threadconf.cgi", "w");
	foreach($threadconf as $tmp) {
		fwrite($fp, implode(',', $tmp)."\n");
	}
	fclose($fp);
	$comment = '<font color="red">VIP機能を変更しました。</font><br>';
}
#==================================================
#　メニュー
#==================================================
if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
$st = ($_GET['page'] - 1) * $thread;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="main.css" type="text/css">
<title>VIP機能変更</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>VIP機能変更</h3>

<form action="?">
	<select name="bbs" id="bbs">
		<option value="jp">jp</option>
		<option value="en">en</option>
		<option value="vn">vn</option>
	</select>
</form>
<script>
$(function() {
	$("#bbs").on("change", function(e) {
		$(this).parents('form').submit();
	}).val("<?php echo "${bbs}"; ?>");
});
</script>

<hr>
<?=$comment?>
<br>
page：<?=$_GET['page']?><br>
<?php
$total = count($PAGEFILE) + $thread - 1;
$total_page = (int)($total/$thread);
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=${bbs}&amp;page=$i\">$i</a> \n";
}
?>
<table border="1" cellspacing="0" cellpadding="2">
<tr><th>スレッドキー</th><th>タイトル</th><th>名無し変更</th><th>強制名無し</th><th>IDなし</th><th>強制sage</th><th>キャップのみレス可</th><th>VIP機能無効</th><th>名前必須</th><th>ゼロセロ</th><th>アップロード</th><th>　</th></tr>
<?php
for ($i = $st; $i < $st+$thread; $i++) {
	if (!isset($PAGEFILE[$i])) break;
	$tmp = $PAGEFILE[$i];
	if (!isset($threadconf[$tmp])) $threadconf[$tmp] = array($tmp,'','',0,0,0,0,0,0,0);
	?>
<tr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<td> <a class="item" href="../post/read.php/<?php echo "${bbs}/${tmp}"?>/l50">#<?php echo "${bbs}${tmp}"; ?></a> </td>
<td><?=$SUBJECT[$tmp]?></td>
<td><input type="text" size="10" name="name_774" value="<?=$threadconf[$tmp][1]?>"></td>
<td><input type="text" size="10" name="force_774" value="<?=$threadconf[$tmp][2]?>"></td>
<td><input type="checkbox" name="no_id"<?php if ($threadconf[$tmp][3]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="sage"<?php if ($threadconf[$tmp][4]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="stars"<?php if ($threadconf[$tmp][5]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="normal"<?php if ($threadconf[$tmp][6]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="name"<?php if ($threadconf[$tmp][7]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="zerothello"<?php if ($threadconf[$tmp][8]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="up"<?php if ($threadconf[$tmp][9]) echo " checked"; ?> value="1"></td>
<td>
 <input type="hidden" name="bbs" value="<?php echo $bbs; ?>">
 <input type="hidden" name="key" value="<?=$tmp?>">
 <input type="hidden" name="mode" value="set">
 <input type="submit" value="設定変更">
</td>
</form>
</tr>
<?php
}
echo "</table>\n";
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=${bbs}&amp;page=$i\">$i</a> \n";
}
echo "</body></html>";
exit;
?>
