function buscar_origen (origen)
{
    $.ajax({
        URL:'',
        type:'POST',
        dataType:'json',
        data:{origen:origen},
    });

}