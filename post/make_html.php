<?php
define('VERSION', '2005/04/23');

// require __DIR__ . '/functions.php';

// メイン処理
$SUBJECT = array();
$threadInfos = loadSubjectFile('../dat', $subjectfile, $SUBJECT);

// $bbsLocales = explode(',', $SETTING['BBS_LOCALES']);
$bbsLocales = array(
    'jp', 'en', 'vn',
);
foreach ($bbsLocales as $locale) {
    $localeDirPath = "../${locale}";
    if (!is_dir($localeDirPath)) {
        mkdir($localeDirPath, 777, true);
    }

    $spLocaleDirPath = "../${locale}/m";
    if (!is_dir($spLocaleDirPath)) {
        mkdir($spLocaleDirPath, 777, true);
    }

    $subbackFile = "${localeDirPath}/subback.html";
    createHtmlKakoLog($subbackFile, $threadInfos, $SUBJECT, $locale);

    $htmlFilePath = "${localeDirPath}/index.html";
    $localeTemplateDir = "../template/${locale}";
    createHtmlIndex($htmlFilePath, $localeTemplateDir, $threadInfos, $SUBJECT, $SETTING, $locale, $localeDirPath, $NOWTIME, $bbs_title);

    $spHtmlFilePath = "${spLocaleDirPath}/index.html";
    $spLocaleTemplateDir = "../template/${locale}/m";
    createHtmlIndex($spHtmlFilePath, $spLocaleTemplateDir, $threadInfos, $SUBJECT, $SETTING, $locale, $localeDirPath, $NOWTIME, $bbs_title, true);
    // createHtmlIndexForSp($spIndexFile, $subjectfile, $SETTING, $locale);
}

#--------書きこみ終了画面
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (strstr(strtolower($userAgent), 'mobile') || (isset($_POST['sp']) && $_POST['sp'] == 'm')) {
    // header("Location: ../{$_REQUEST['bbs']}/m/index.html");
    // exit;
    $INDEXFILE = "../{$_REQUEST['bbs']}/m/index.html";
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
