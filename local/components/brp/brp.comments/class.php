<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

Loader::includeModule('highloadblock');
$hlblock = HL\HighloadBlockTable::getById(1)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock); //генерация класса
$entityClass = $entity->getDataClass();

/**
 * Class Comments - Комментарии
 */
class Comments extends \CommentsTable
{
    /**
     * Получить список комментариев
     * @param string $type - IBLOCK/PAGE к чему привязывать комментарии (Элемент или страница)
     * @param string $block_id - ID инфоблока / Код страницы
     * @return array - Массив комментариев
     */
    public static function getComments($type, $block_id = '')
    {
        $data = array(
            'select' => array('*', new Entity\ExpressionField('FULL_PATH', 'CONCAT(%s,%s)', array('UF_MPATH', 'ID'))),
            'order' => array(
                'FULL_PATH' => 'ASC',
                'UF_DATE' => 'DESC'
            ),
            'filter' => array()
        );

        switch ($type) {
            case "IBLOCK":
                $data['filter']['=UF_IBLOCK'] = $block_id;
                break;
            case "PAGE":
                $data['filter']['=UF_BLOCK'] = $block_id;
                break;
        }

        $resComments = self::getList($data);
        $comments = array();

        while ($arComment = $resComments->fetch()) {
            $arComment['LEVEL'] = substr_count($arComment['UF_MPATH'], '.');
            $arComment['UF_DATE'] = (string)$arComment['UF_DATE'];
            $arComment['editMode'] = false;
            if ($arComment['UF_USER_ID'])
                $arComment['UF_FIO'] = self::getUserFio($arComment['UF_USER_ID']);
            $comments[] = $arComment;
        }

        return $comments;
    }

    /**
     * Добавить новый комментарий
     * @param int $parent_id - ID комментария родителя
     * @param string $fio - Ф.И.О пользователя
     * @param string $text - Текст комментария
     * @param string $block - ID инфоблока / код страницы
     * @param int $user_id - ID пользователя
     */
    public static function addComment($parent_id, $fio, $text, $type, $block_id = '', $user_id = 0)
    {
        $data = array(
            'UF_PARENT' => $parent_id,
            'UF_MPATH' => self::_generateMPath($parent_id),
            'UF_DATE' => new DateTime(),
            'UF_FIO' => $fio,
            'UF_TEXT' => $text,
        );

        switch ($type) {
            case "IBLOCK":
                $data['UF_IBLOCK'] = $block_id;
                break;
            case "PAGE":
                $data['UF_BLOCK'] = $block_id;
                break;
        }
        if ($user_id)
            $data['UF_USER_ID'] = $user_id;

        $res = self::add($data);

        return $res;
    }

    /**
     * Получить комментарий
     * @param $id - ID комментарий
     * @return mixed - Массив с данными комментария
     */
    public static function getComment($id)
    {
        $comment = self::getById($id);

        return $comment->fetch();
    }

    /**
     * Получить Ф.И.О. пользователя по его ID
     * @param int $user_id - ID пользователя
     * @return bool|string - Ф.И.О.
     */
    private static function getUserFio($user_id = 1)
    {
        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();

        if ($arUser)
            return $arUser['NAME'] . " " . $arUser['LAST_NAME'];

        return false;
    }

    /**
     * Генерация материального пути для записи
     * @param $parent_id - ID родителя
     * @return string - Materialized path
     */
    private static function _generateMPath($parent_id)
    {
        if ($parent_id == 0)
            return "";
        $parent = self::getComment($parent_id);

        return $parent['UF_MPATH'] . $parent_id . ".";
    }


}