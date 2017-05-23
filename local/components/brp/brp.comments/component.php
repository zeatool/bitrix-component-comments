<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('ajax') == 'Y') {

    $arResult['JSON_DATA'] = array(
        'STATUS' => true,
        'MESSAGE' => '',
        'ITEMS' => array()
    );

    try {
        if ($request->get('add') == 'Y') {

            $user_id = $USER->IsAuthorized() ? $USER->GetID() : 0;

            $parent_id = intval($request->get('parent_id'));
            $text = trim($request->get('text'));
            if (strlen($text) == 0)
                throw new Exception("Текст не может быть пустым!");

            $block = $request->get('block');
            $fio = trim($request->get('fio'));

            if (!$user_id && strlen($fio) == 0)
                throw new Exception("ФИО не может быть пустым");

            Comments::addComment($_REQUEST['parent_id'], $_REQUEST['fio'], $_REQUEST['text'], $_REQUEST['block'], $user_id);
        } else {
            $arResult['JSON_DATA']['ITEMS'] = Comments::getComments($_REQUEST['block']);
        }
    } catch (Exception $e) {
        $arResult['JSON_DATA']['STATUS'] = false;
        $arResult['JSON_DATA']['MESSAGE'] = $e->getMessage();
    }

    $APPLICATION->RestartBuffer();
    $this->IncludeComponentTemplate("ajax");
    exit();
}

$this->IncludeComponentTemplate();
?>