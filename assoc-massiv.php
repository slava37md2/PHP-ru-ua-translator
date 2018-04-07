<?php
	ini_set('memory_limit', '-1');//Эта строчка нужна т.к. скрипт потребляет около 300М памяти
	$mem_start = memory_get_usage();
	
	$spisok_rus = file_get_contents("russian-utf8.txt");
	$words_rus = explode("\r\n", $spisok_rus, 532797);
	$spisok_ukr = file_get_contents("ukrainian-utf8.txt");
	$words_ukr = explode("\r\n", $spisok_ukr, 532797);
	
	for($i=0; $i<532797; $i++){
		$assoc_words[$words_rus[$i]] = $words_ukr[$i];//Создаём ассоциативный массив
	}

	echo "<br>".(memory_get_usage() - $mem_start)."<br>";
	
	//$fraza = "я Люблю Авиастроительный институт, очень сильно. Но не знаю, как его найти<a href=http://vasya.ru><img src=http://vasya.ru/1.jpg></a>. Я хочу вступить в КПСС. Просунув руку я увидел висевшую ногу.";
	//echo $fraza, "<br>";
	$fraza = file_get_contents("https://habrahabr.ru/post/352976/");
	
	$fraza = str_replace ( '<', " <", $fraza );$fraza = str_replace ( '>', "> ", $fraza );$fraza = str_replace ( '.', " . ", $fraza );$fraza = str_replace ( '!', " ! ", $fraza );$fraza = str_replace ( '-', " - ", $fraza );
	$fraza = str_replace ( ';', " ; ", $fraza );$fraza = str_replace ( ':', " : ", $fraza );$fraza = str_replace ( ',', " , ", $fraza );$fraza = str_replace ( ')', " ) ", $fraza );$fraza = str_replace ( '(', " ( ", $fraza );
	$fraza = str_replace ( '"', ' " ', $fraza );$fraza = str_replace ( "'", " ' ", $fraza );$fraza = str_replace ( '«', ' « ', $fraza );$fraza = str_replace ( "»", " » ", $fraza );$fraza = str_replace ( "?", " ? ", $fraza );
	$words = explode(" ", $fraza);
	
	$i = 0; $count_words = count($words); $result = ""; 
	while($i<$count_words){
		
		$str = $words[$i];
		if (mb_strtolower(mb_substr($str, 0, 1, 'UTF8'), 'UTF8') != mb_substr($str, 0, 1, 'UTF8')) { // Если первая буква большая, то и в украинском слове должна быть первая большая
			$str = mb_strtolower(mb_substr($str, 0, 1, 'UTF8'), 'UTF8'). mb_substr($str, 1, null, 'UTF8');
			$a_w = $assoc_words[$str]; // Переводим
			$a_w = mb_strtoupper(mb_substr($a_w, 0, 1, 'UTF8'), 'UTF8'). mb_substr($a_w, 1, null, 'UTF8');
		}
		else $a_w = $assoc_words[$words[$i]]; // Переводим
		
		if( $a_w == "" ) $result .= $words[$i]." "; // Если этого слова нет в словаре, то вставляем без перевода
		else $result .= $a_w." ";
		$i++;
	}
	$fraza = $result;
	$fraza = str_replace ( ' <', "<", $fraza );$fraza = str_replace ( '> ', ">", $fraza );$fraza = str_replace ( ' . ', ".", $fraza );$fraza = str_replace ( ' ! ', "!", $fraza );$fraza = str_replace ( ' - ', "-", $fraza );
	$fraza = str_replace ( ' ; ', ";", $fraza );$fraza = str_replace ( ' : ', ":", $fraza );$fraza = str_replace ( ' , ', ",", $fraza );$fraza = str_replace ( ' ) ', ")", $fraza );$fraza = str_replace ( ' ( ', "(", $fraza );
	$fraza = str_replace ( ' " ', '"', $fraza );$fraza = str_replace ( " ' ", "'", $fraza );$fraza = str_replace ( ' « ', '«', $fraza );$fraza = str_replace ( " » ", "»", $fraza );$fraza = str_replace ( " ? ", "?", $fraza );
	echo $fraza;
?>