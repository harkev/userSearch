<?php
use Bitrix\Main;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}


class ShowUser extends \CBitrixComponent implements Bitrix\Main\Engine\Contract\Controllerable, Bitrix\Main\Errorable
{
	public function executeComponent() : void
	{
		try
		{
			$this->loadModules();
            $this->getResult();
            $this->showFilter();
            $this->includeComponentTemplate();
		}
		catch (Main\SystemException $exception)
		{
			$this->sendResponse([
				'error' => [
					'code' => (string)$exception->getCode(),
					'message' => $exception->getMessage(),
				],
			]);
			// todo show error, may be loggers
		}
	}

	protected function loadModules() : void
	{
		$requiredModules = $this->getRequiredModules();

		foreach ($requiredModules as $requiredModule)
		{
			if (!Main\Loader::includeModule($requiredModule))
			{
				$message = "Не подключается модуль ".$requiredModule;

				throw new Main\SystemException($message);
			}
		}
	}

	protected function getRequiredModules() : array
	{
		return [
            'main',
		];
	}

    protected function getResult($filter = []) : void
    {
        $res = Bitrix\Main\UserTable::getList(Array(
            "select" => $this->getSelect(),
            "filter" => $filter,
        ));
        while ($arUser = $res->fetch()) {
            $arResult["ITEMS"][] = $arUser;
        }
        $this->arResult = $arResult;
    }

    protected function getSelect() : array {
        return [
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
            'PERSONAL_BIRTHDAY',
            'LOGIN',
            'EMAIL'
        ];
    }

    protected function  showFilter(): void{
	    $arFilter = $this->arParams['FILTER'];
	    if (empty($arFilter))
	        return;

	    $htmlFilter = [];
        $htmlFilter['START'] = '<div id="filter_show_user">';
	    foreach ($arFilter as $filter){
	        if ($filter =='FIO'){
                $htmlFilter[$filter] = ' <input type="text" size="10" placeholder="Имя" name="NAME">';
                $htmlFilter[$filter].= ' <input type="text" size="15" placeholder="Фамилия" name="LAST_NAME">';
                $htmlFilter[$filter].= ' <input type="text" size="15" placeholder="Отчество" name="SECOND_NAME">';
            }else{
                $htmlFilter[$filter] = ' <input type="text" size="15" placeholder="'.$filter.'" name="'.$filter.'">';
            }
        }

        $htmlFilter['BUTTON'] =  '<button id="userfilter">Поиск</button>';
        $htmlFilter['END'] = '</div>';
        $this->arResult['HTMLFILTER'] = $htmlFilter;
    }
    
    protected ErrorCollection $errorCollection;

    public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();
        return $arParams;
    }

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }

    // Описываем действия
    public function configureActions(): array
    {
        return [
            'send' => [
                'prefilters' => [
                    // здесь указываются опциональные фильтры, например:
                    new ActionFilter\Authentication(), // проверяет авторизован ли пользователь
                ]
            ]
        ];
    }

    public function sendAction(): array
    {
        $filter = ['LOGIC'=>'AND'];
        foreach ($_POST as $key=>$val){
            if (!empty($val))
                $filter[$key]=$val;
        }
        $this->getResult($filter);
        try {
            return [
                "result" => $this->arResult,
            ];
        } catch (Exceptions\EmptyEmail $e) {
            $this->errorCollection[] = new Error($e->getMessage());
            return [
                "result" => "Произошла ошибка",
            ];
        }
    }

}
