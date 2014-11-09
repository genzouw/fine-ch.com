<?php

    $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

    $selectedLang = 'jp';
    $selectableLangs = array(
        'ja' => 'jp',
        'en' => 'en',
        'vn' => 'vn',
    );

    foreach ($languages as $lang) {
        $lang = preg_replace('/^(..).*/u', '\1', $lang);
        $lang = strtolower($lang);

        if (array_key_exists($lang, $selectableLangs)) {
            $selectedLang = $selectableLangs[$lang];
            break;
        }
    }

    doRedirect("/${selectedLang}/index.html");

    /**
	 * リダイレクトを行う。
	 *
	 *
	 * @param
	 * @return
	 */
    function doRedirect($strUrl)
    {
        if (!headers_sent($filename, $linenum)) {
            header("Location:".$strUrl);
            exit;

        // おそらく、ここでエラー処理を行うでしょう。
        } else {

            echo "$filename の $linenum 行目でヘッダがすでに送信されています。\n" .
                  "リダイレクトできません。代わりにこの <a " .
                  "href=\"http://www.example.com\">リンク</a> をクリックしてください。\n";
            exit;
        }
    }
