<?php
// SAMPLE1 店舗SEQを設定
$storeseq = 'root_group';
?><!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="viewport" content="width-device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
		<!-- Bootstrap -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<title>Top - SQLite Sample</title>
		<style>
		footer {
			margin-top: 1em;
			background-color: #f5f5f5;
			text-align: center;
		}
		</style>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>

	<body>
		<nav class="navbar navbar-default">
			<div class="navbar-header">
				<button class="navbar-toggle" data-toggle="collapse" data-target=".target">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="">SQLite Sample</a>
			</div>
			<div class="collapse navbar-collapse target">
				<ul class="nav navbar-nav">

				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="javascript:void(0);"></a></li>
				</ul>
			</div>
		</nav>

		<div class="container-fluid">
			<h2>Top</h2>

      <div class="row">
          <div class="col-sm-offset-2 col-sm-4">
              クリック計測用のボタン
          </div>
          <div class="col-sm-4 col-sm-offset-2">
              <div class="form-group">
                  <!-- SAMPLE2 ボタンクリック時の情報を集計する場合はこちらが利用可能 -->
                  <a href="http://www.amazon.com/" id="click-count-button" class="btn btn-primary">ほげほげ</a>
                  <script>
                  // ;$(function() {
                    // $("#click-count-button").on("click", function(e) {
											// e.preventDefault();
											// var url = $(this).attr("href");
                      // $.ajax({
                        // url: "/fine-ch.com/az/analyzer/write.php?guid=ON&act=click&storeseq=<?php echo $storeseq; ?>&url=" + url,
                        // type: "GET",
                        // complete: function( data, status ) {
                          // console.log(data);
                          // console.log(status);
													// // document.location = url;
                        // }
                      // });
                    // });
                  // });
                  </script>
              </div>
          </div>
      </div>
		</div>

		<footer id="footer">
			<p>Copyright © 2014-2014 genzouw All Rights Reserved.</p>
			<p>( twitter:<a href="https://twitter.com/genzouw">@genzouw</a> , facebook:<a href="https://www.facebook.com/genzouw">genzouw</a>, mailto:<a href="mailto:genzouw@gmail.com">genzouw@gmail.com</a> )</p>
		</footer>

		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <!-- SAMPLE3 PV数を計測するためには、以下の2行を記述する -->
    <script type="text/javascript" src="/fine-ch.com/az/analyzer/tracker.js?storeseq=<?php echo $storeseq; ?>"></script>
    <noscript><img src="/fine-ch.com/az/analyzer/write.php?guid=ON&act=img&storeseq=<?php echo $storeseq; ?>" width="0" height="0" alt="tracker" /></noscript>
	</body>
</html>

