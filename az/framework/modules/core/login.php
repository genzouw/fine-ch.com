<?php

class login
{

    //---------------------------------------------------------
    //	ログイン処理
    //---------------------------------------------------------

    public static function execute($work_dir,$tmpl_dir)
    {

        // 変数を初期化
        $user = '';
        $pass = '';

        // フォームデータが存在する時
        if (isset($_POST['user']) && isset($_POST['pass'])) {

            // userを取得
            $user = md5($_POST['user']);

            // パスワードを取得
            $pass = md5($_POST['pass']);
        }

        // Cookieが存在する時は変数に展開
        elseif (isset($_COOKIE['ls_login'])) {parse_str($_COOKIE['ls_login']);}

        // ユーザーとパスワードをチェック。NGの時はログインフォームを表示
        // ユーザーとグループをチェック。NGの時はログインフォームを表示
        if (!self::pass_check($user,$pass,$work_dir) || !self::group_check($user,$pass,$work_dir)) {
            exit(self::login_form($tmpl_dir));
        }

        // Cookieの有効期限を設定
        $c_time = $_SERVER['REQUEST_TIME'] + 3600 * 24 * 365;

        // 現在のホストドメインを取得
        $c_host = $_SERVER['SERVER_NAME'];

        // ドメインを分割
        $c_dm = array_reverse(explode('.',$c_host));

        // localhostの時はドメイン指定を削除
        if (count($c_dm) === 1) {$c_host = '';}

        // 2nd LD の長さが3以上の時（属性無し）
        elseif (strlen($c_dm[1]) > 2) {$c_host = '.' . $c_dm[1] . '.' . $c_dm[0];}

        // 2nd LD の長さが2以下の時（属性有り）
        else{$c_host = '.' . $c_dm[2] . '.' . $c_dm[1] . '.' . $c_dm[0];}

        // クロスドメイン用ヘッダーを出力
        header("P3P: CP='UNI CUR OUR'");

        // Cookieを発行
        setcookie('ls_login',"user=$user&pass=$pass&group=$group",$c_time,'/',$c_host);

    }

    //---------------------------------------------------------
    //	パスチェック
    //---------------------------------------------------------
    public static function pass_check($user,$pass,$work_dir)
    {

        // iniファイル名を定義
        $pass_ini = $work_dir . '/configs/pass.ini';

        // iniファイルが存在しなければ認証しない
        if (!file_exists($pass_ini)) {return;}

        // user＆passファイルをパース
        $pass_d = parse_ini_file($pass_ini);

        // ファイルデータを読み取る
        foreach ($pass_d as $key => $val) {

            // ユーザー名とパスワードが一致した場合はtrueを返す
            if ($user === md5($key) and $pass === md5($val)) {return true;}

        }

        return false;

    }

    //---------------------------------------------------------
    //	groupチェック
    //---------------------------------------------------------
    public static function group_check($user,$pass,$work_dir)
    {
        $findGroup = self::findGroup($user, $pass, $work_dir);

        return !is_null($findGroup) && !empty($findGroup);
    }

    public static function findGroup($user,$pass,$work_dir)
    {
        // iniファイル名を定義
        $group_ini = $work_dir . '/configs/group.ini';

        // iniファイルが存在しなければ認証しない
        if (!file_exists($group_ini)) {return '';}

        // user＆groupファイルをパース
        $group_d = parse_ini_file($group_ini);

        // ファイルデータを読み取る
        foreach ($group_d as $key => $val) {

            $userPart = explode(':', $key)[0];
            $passPart = explode(':', $key)[1];

            // ユーザー名とグループが一致した場合はtrueを返す
            if ($user === md5($userPart) && $pass === md5($passPart)) {
                return $val;
            }
        }

        return '';

    }

    //---------------------------------------------------------
    //	ログインフォーム表示
    //---------------------------------------------------------

    public static function login_form($tmpl_dir)
    {

        // グローバル変数を定義
        global $path;

        // テンプレートHTMLを取得
        $tmpl_htm = $tmpl_dir . '/login.htm';

        // テンプレートHTMLを読み込み
        $tmpl_d = file_get_contents($tmpl_htm) or die("$tmpl_htm File Read Error");

        // スクリプトパスを取得
        $scr_php = $path['scr_php'];
        $scr_dir = $path['scr_dir'];

        // テンプレート変数を初期化
        $user = '';
        $pass = '';

        // テンプレートを出力
        eval("echo <<<_HTML_\n$tmpl_d\n_HTML_;\n");

    }

}
