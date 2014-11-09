<?php
define('VERSION', '2005/04/23');

/**
 * 過去ログメニューをかーくー
 */
function createHtmlKakoLog($htmlFilePath, $threadInfos, $subject)
{
    $fp = @fopen($htmlFilePath, "w");
    if (!$fp) exit;
    fputs($fp, '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="pragma" content="no-cache"><meta http-equiv="Cache-Control" content="no-cache"></head><body><font size="2">');
    $count = 0;
    foreach ($threadInfos as $tmp) {
        $count++;
        $dat = str_replace(".dat", "", $tmp);
        fputs($fp, "<a href=\"/post/read.php/$_REQUEST[bbs]/$dat/l50\">$count: $subject[$tmp]</a><br>\n");
    }
    fputs($fp, '<div align="right"><a href="./kako/"><b>過去ログ倉庫はこちら</b></a></font>');
    fputs($fp, "</body></html>\n");
    fclose($fp);
}

/**
 * index.html出力処理
 */
function createHtmlIndex($htmlFilePath, $threadInfos, $subject, $setting, $locale, $localeDirPath, $pageHtmlDir, $nowtime, $bbs_title)
{
    $localeTemplateDir = "../{$setting['BBS_TEMPLATE_DIR']}/${locale}";

    $fp = fopen($htmlFilePath, "w");
    #--------ヘッダ＆上の広告
    list($header, $footer) = explode('<CUT>', implode('', file("${localeTemplateDir}/index.txt")));
    $header = str_replace("<BBS_TITLE>", $setting['BBS_TITLE'], $header);
    $header = str_replace("<BBS_TEXT_COLOR>", $setting['BBS_TEXT_COLOR'], $header);
    $header = str_replace("<BBS_MENU_COLOR>", $setting['BBS_MENU_COLOR'], $header);
    $header = str_replace("<BBS_LINK_COLOR>", $setting['BBS_LINK_COLOR'], $header);
    $header = str_replace("<BBS_ALINK_COLOR>", $setting['BBS_ALINK_COLOR'], $header);
    $header = str_replace("<BBS_VLINK_COLOR>", $setting['BBS_VLINK_COLOR'], $header);
    $header = str_replace("<BBS_BG_COLOR>", $setting['BBS_BG_COLOR'], $header);
    $header = str_replace("<BBS_BG_PICTURE>", $setting['BBS_BG_PICTURE'], $header);
    $header = str_replace("<BBS_TITLE_NAME>", $bbs_title, $header);
    $head = implode('', file("${localeTemplateDir}/head.txt"));
    $header = str_replace("<GUIDE>", $head, $header);
    $option = implode('', file("${localeTemplateDir}/option.txt"));
    $header = str_replace("<OPTION>", $option, $header);
    $putad = implode('', file("${localeTemplateDir}/putad.txt"));
    $header = str_replace("<PUTAD>", $putad, $header);
    fputs($fp, $header);
    $headad = implode('', file("${localeTemplateDir}/headad.txt"));
    if ($headad) {
        fputs($fp, '<br><table border="1" cellspacing="7" cellpadding="3" width="95%" bgcolor="'.$setting['BBS_MENU_COLOR']."\" align=\"center\">\n <tr>\n  <td>\n");
        fputs($fp, $headad);
        fputs($fp, "\n  </td>\n </tr>\n</table><br>\n");
    }
    #--------スレッド一覧
    $menu = ''
        . '<a name="menu"></a>'
        . '<table border="1" cellspacing="7" cellpadding="3" width="95%" bgcolor="'.$setting['BBS_MENU_COLOR'].'" align="center">'
        . '<tr>'
        . '<td><font size="2">';
    fputs($fp, $menu);
    $i = 1;
    foreach ($threadInfos as $tmp) {
        $threadKey = str_replace(".dat", "", $tmp);
        if ($i <= $setting['BBS_THREAD_NUMBER']) {
            fputs($fp, "   <a href=\"/post/read.php/$_REQUEST[bbs]/$threadKey/l50\" target=\"body\">$i:</a> <a href=\"#$i\">$subject[$tmp]</a>　\n");
        } elseif ($i <= $setting['BBS_MAX_MENU_THREAD']) {
            fputs($fp, "   <a href=\"/post/read.php/$_REQUEST[bbs]/$threadKey/l50\" target=\"body\">$i: $subject[$tmp]</a>　\n");
        } else break;
        $i++;
    }
    $count_end = --$i;
    fputs($fp, "   <div align=\"right\"><a href=\"subback.html\"><b>スレッド一覧はこちら</b></a></div>\n  </td>\n </tr>\n</table><br>\n");
    #--------一覧下の広告
    #--------スレッド表示
    $i = 1;
    $form_txt = implode('', file("${localeTemplateDir}/form.txt"));
    $fp2  = fopen("${localeDirPath}/threadconf.cgi", "r");
    $array = array();
    while ($list = fgetcsv($fp2, 1024)) {
        $vip[$list[0]] = $list;
    }
    fclose($fp2);
    foreach ($threadInfos as $tmp) {
        $threadKey = str_replace(".dat", "", $tmp);
        $enctype = 'application/x-www-form-urlencoded';
        $file_form = '';
        if (UPLOAD or $vip[$threadKey][9]) {
            $enctype = 'multipart/form-data';
            $file_form = '<input type=file name=file size=50><br>';
        }

        
        if (!dir($pageHtmlDir)) {
            mkdir($pageHtmlDir, 777, true);
        }

        $pageHtmlFile = "${pageHtmlDir}${threadKey}.html";
        if (!file_exists($pageHtmlFile)) {
            continue;
        }

        $log = file($pageHtmlFile);

        if ($i == 1) {
            $j = ($count_end <= $setting['BBS_THREAD_NUMBER']) ? $count_end : $setting['BBS_THREAD_NUMBER'];
        } else {
            $j = $i - 1;
        }

        if (count($threadInfos) == $i) {
            $k = 1;
        } elseif ($i >=  $setting['BBS_THREAD_NUMBER']) {
            $k = 1;
        } else {
            $k = $i + 1;
        }

        $first = array_shift($log);
        $first = str_replace('$ANCOR', $i, $first);
        $first = str_replace('$FRONT', $j, $first);
        $first = str_replace('$NEXT', $k, $first);
        fputs($fp, $first);
        foreach ($log as $loglist) {
            fputs($fp, $loglist);
        }
        $form = str_replace('<BBS>', $locale, $form_txt);
        $form = str_replace('<KEY>', $threadKey, $form);
        $form = str_replace('<TIME>', $nowtime, $form);
        $form = str_replace('<PATH>', "${localeDirPath}/", $form);
        $form = str_replace('<ENCTYPE>', $enctype, $form);
        $form = str_replace('<FILE_FORM>', $file_form, $form);
        fputs($fp, $form);
        if (++$i >  $setting['BBS_THREAD_NUMBER']) break;
    }
    #--------新規作成画面＆一番下の広告＆バージョン表示
    $footer = str_replace('<BBS_MAKETHREAD_COLOR>', $setting['BBS_MAKETHREAD_COLOR'], $footer);
    $footer = str_replace('<BBS>', $locale, $footer);
    $footer = str_replace('<VERSION>', VERSION, $footer);
    fputs($fp, $footer);
    fclose($fp);
}

/**
 * index.html出力処理（スマートフォン用）
 */
function createHtmlIndexForSp($subjectFilePath, $setting, $imodeFile)
{
    # i-mode用index
    $th_titles = file($subjectFilePath);
    $end = count($th_titles);
    $data = "<html><head><title>$_REQUEST[bbs] スレッド一覧</title></head><body>$setting[BBS_TITLE]<hr>";
    for ($i = 1; $i < 11; $i++) {
        if(!isset($th_titles[$i-1]) or !$th_titles[$i-1]) break;
        list($id, $sub) = explode("<>", $th_titles[$i-1]);
        $id = str_replace(".dat", "", $id);
        $data .= $i.": <a href=/post/r.php/$_REQUEST[bbs]/$id/>".rtrim($sub).'</a><br>';
    }
    $data .= "<hr><a href=/post/p.php/$_REQUEST[bbs]/$i>続き</a> <a href=..//post/b.php/$_REQUEST[bbs]/>新ｽﾚ</a></body></html>\n";
    $fp = fopen ($imodeFile, "w");
    fputs($fp, $data);
    fclose($fp);
}

// メイン処理
$bbsLocales = explode(',', $SETTING['BBS_LOCALES']);
foreach ($bbsLocales as $locale) {
    $localeDirPath	= "../${locale}";

    if (!is_dir($localeDirPath)) {
        mkdir($localeDirPath, 777, true);
    }

    $pageHtmlDir = "${localeDirPath}/html/";
    $indexFile = "${localeDirPath}/index.html";

    createHtmlKakoLog($SUBFILE, $threadInfos, $SUBJECT);
    createHtmlIndex($indexFile, $threadInfos, $SUBJECT, $SETTING, $locale, $localeDirPath, $pageHtmlDir, $NOWTIME, $bbs_title);
    createHtmlIndexForSp($subjectfile, $SETTING, $IMODEFILE);
}

#--------書きこみ終了画面
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if (strstr($userAgent, 'DoCoMo') || strstr($userAgent, 'J-PHONE') || strstr($userAgent, 'UP.Browser')) {
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['SCRIPT_NAME']))."/$_REQUEST[bbs]/m/");
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
?>
