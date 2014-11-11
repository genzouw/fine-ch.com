<?php
define('VERSION', '2005/04/23');

require __DIR__ . '/functions.php';

// メイン処理
$bbsLocales = explode(',', $SETTING['BBS_LOCALES']);
foreach ($bbsLocales as $locale) {
    $localeDirPath	= "../${locale}";

    if (!is_dir($localeDirPath)) {
        mkdir($localeDirPath, 777, true);
    }

    $pageHtmlDir = "${localeDirPath}/html/";
    $indexFile = "${localeDirPath}/index.html";

    $subbackFile	= "${localeDirPath}/subback.html";
    $spIndexFile	= "${localeDirPath}/m/index.html";

    createHtmlKakoLog($subbackFile, $threadInfos, $SUBJECT, $locale);
    createHtmlIndex($indexFile, $threadInfos, $SUBJECT, $SETTING, $locale, $localeDirPath, $pageHtmlDir, $NOWTIME, $bbs_title);
    // createHtmlIndexForSp($spIndexFile, $subjectfile, $SETTING, $locale);
}

#--------書きこみ終了画面
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (strstr(strtolower($userAgent), 'mobile')) {
    header("Location: ../post/p.php/{$_REQUEST['bbs']}/");
    exit;
}

setcookie ("PON", $HOST, $NOWTIME+3600*24*90, "/");
header("Content-Type: text/html; charset=UTF-8");
?>
<html>
<head>
<title>書きこみました。</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?=$set_cookie?>
<meta http-equiv="refresh" content="1;URL=<?=$INDEXFILE?>?">
</head>
<body>書きこみが終わりました。<br>
<br>
画面を切り替えるまでしばらくお待ち下さい。<br>
<br><br><br>
</body>
</html>
<?php
exit;
