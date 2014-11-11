<?php
$HOST = gethostbyaddr($_SERVER['REMOTE_ADDR']);
#====================================================
#　日付・時刻を設定
#====================================================
$NOWTIME = time();
$wday = array('日','月','火','水','木','金','土');
$today = getdate($NOWTIME);
$JIKAN = $today['hours'];
$DATE = date("Y/m/d(", $NOWTIME).$wday[$today['wday']].date(") H:i:s", $NOWTIME);
#====================================================
#　各種ＰＡＴＨ生成
#====================================================
$DATPATH	= "../dat/";
$IMGPATH	= "../img/";
$IMGPATH2	= "../img2/";

$subjectfile = "${DATPATH}subject.txt";

#====================================================
#　初期情報の取得（設定ファイル）
#====================================================
function loadSetting( $locale ) {
    global $SETTING;
    $path = "../${locale}/";
    $set_file = $path . "SETTING.TXT";
    if (is_file($set_file)) {
        $set_str = file($set_file);
        foreach ($set_str as $tmp){
            $tmp = trim($tmp);
            list ($name, $value) = explode("=", $tmp);
            $SETTING[$name] = $value;
        }
    } else {
        DispError("ＥＲＲＯＲ！","ＥＲＲＯＲ：ユーザー設定が消失しています！$path");
    }
}
