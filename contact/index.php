<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
?>Хотите нас найти?<br>
Свяжитесь с нами!<br>
<br>
 <?$APPLICATION->IncludeComponent(
	"brp:brp.comments",
	"",
	Array(
		"BLOCK_COMMENTS" => "contact",
		"TYPE_COMMENTS" => "PAGE"
	)
);?><br>
 <br><?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>