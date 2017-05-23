<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"TYPE_COMMENTS" => array(
			"NAME" => "Тип объекта комментирования",
			"TYPE" => "LIST",
			"PARENT" => "BASE",
            "VALUES" => array(
                "IBLOCK" => "Элемент инфоблока",
                "PAGE" => "Страница"
            )
		),
		"BLOCK_COMMENTS" => array(
			"NAME" => "Объект комментирования",
			"TYPE" => "STRING",
			"PARENT" => "BASE",
		)
    )
);
