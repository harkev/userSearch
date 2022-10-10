<?php
if (!empty($arResult['HTMLFILTER'])){
    foreach ($arResult['HTMLFILTER'] as $htmlFilter)
        echo $htmlFilter;
}

if (!empty($arResult['ITEMS'])):?>
    <table class="showUser">
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Дата рождения</th>
                <th>Логин</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
     <? foreach ($arResult['ITEMS'] as $item):?>
        <tr>
            <td><?=$item['NAME']?> <?=$item['LAST_NAME']?> <?=$item['SECOND_NAME']?></td>
            <td><?=$item['PERSONAL_BIRTHDAY']?></td>
            <td><?=$item['LOGIN']?></td>
            <td><?=$item['EMAIL']?></td>
        </tr>
     <? endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

