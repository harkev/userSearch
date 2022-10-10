$( document ).ready(function() {
    $('#userfilter').click( function () {
        $filterElement = $("#filter_show_user").find(":input");
        var data = $filterElement.serialize();
        BX.ajax.runComponentAction("userSearch:search.user", "send", {
            mode: "class",
            data: data
        }).then(function (response) {
            if (response.status == "success"){
                if (response.data.result == null){
                    alert('Не найден пользователь');
                } else{
                    items = response.data['result']['ITEMS'];
                    htmlUser='<tbody>';
                    items.forEach(function(item){
                        htmlUser+='<tr>';
                        htmlUser+='<td>'+item['NAME']+' '+item['LAST_NAME']+' '+item['SECOND_NAME']+'</td>';
                        htmlUser+='<td>'+item['PERSONAL_BIRTHDAY']+'</td>';
                        htmlUser+='<td>'+item['LOGIN']+'</td>';
                        htmlUser+='<td>'+item['EMAIL']+'</td>';
                        htmlUser+='</tr>';

                    });
                    htmlUser+='</tbody>';
                    $('.showUser tbody').remove();
                    $('.showUser').append(htmlUser);
                }
            }else{
                alert('Что-то пошло не так, попробуйте снова');
            }
        });
        return false;
    });
});