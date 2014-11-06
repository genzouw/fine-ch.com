<?php

	doRedirect("./board");

	/**
	 * リダイレクトを行う。
	 * 
	 *
	 * @param 
	 * @return 
	 */
	function doRedirect($strUrl){

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


?>