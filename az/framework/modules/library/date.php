<?php

class Date
{
	
	//---------------------------------------------------------
	//  現在時刻の取得
	//---------------------------------------------------------
	
	function now_date()
	{
		
		// 現在時刻を返す
		return explode(' ',gmdate('Y m d D H i s',$_SERVER['REQUEST_TIME'] + $_SERVER['GMT_DIFF'] * 60 * 60));
		
	}
	
	
	//---------------------------------------------------------
	//  前日の日付取得
	//---------------------------------------------------------
	
	function back_date($days = 1)
	{
		
		// 先日日付を返す
		return explode(' ',gmdate('Y m d',strtotime("-$days days") + $_SERVER['GMT_DIFF'] * 60 * 60));
		
	}
	
	
	//---------------------------------------------------------
	//  月の最終日の取得
	//---------------------------------------------------------
	
	function month_days($y,$m)
	{
		
		// 配列を定義
		$md = array(
			
			 '1' => 31,
			 '2' => 28,
			 '3' => 31,
			 '4' => 30,
			 '5' => 31,
			 '6' => 30,
			 '7' => 31,
			 '8' => 31,
			 '9' => 30,
			'10' => 31,
			'11' => 30,
			'12' => 31
			
		);
		
		// 2月以外の時はそのまま値を返す
		if($m != 2){return $md[$m];}
		
		// 2月の時
		else
		{
			
			// 閏年フラグを取得
			$yu = $y % 4;
			
			// 閏年の時は29を返す
			if($yu == 0){return 29;}
			
			// 閏年以外の時は28を返す
			else{return 28;}
			
		}
		
	}
	
	
	//---------------------------------------------------------
	//  年月日の曜日を取得
	//---------------------------------------------------------
	
	function week_day($y,$m,$d)
	{
		
		// 閏年用に年と月を調整
		if($m == 1 or $m == 2){$y--;$m += 12;}
		
		// 曜日コードを算出
		$wd = ($y + intval($y / 4) - intval($y / 100) + intval($y / 400) + intval((13 * $m + 8) / 5) + $d) % 7;
		
		// 配列を定義
		$wde_a = array('sun','mon','tue','wed','thu','fri','sat');
		$wdj_a = array('日','月','火','水','木','金','土');
		
		// 曜日を取得
		$wde = $wde_a[$wd];
		$wdj = $wdj_a[$wd];
		
		// 曜日を返す
		return array($wd,$wde,$wdj);
		
	}
	
}

