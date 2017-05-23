<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
?>А наш сайтик лучше всех!<br>
Сайтик ждет большой успех!<br>
<br>
<br>
<?$APPLICATION->IncludeComponent(
	"brp:brp.comments", 
	".default", 
	array(
		"BLOCK_COMMENTS" => "about",
		"COMPONENT_TEMPLATE" => ".default",
		"TYPE_COMMENTS" => "PAGE"
	),
	false
);?><br>
<br><?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>