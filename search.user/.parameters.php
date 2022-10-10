<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$selectUser = [
    'FIO' => 'ФИО',
    'PERSONAL_BIRTHDAY' => 'Дата рождения',
    'LOGIN' => 'Логин',
    'EMAIL' => 'Емаил',
];
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "FILTER" => array(
            "PARENT" => "BASE",
            "NAME" => "Фильтрация по полям",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $selectUser,
        ),
    ),
);
?>