$(document).ready(function(){
    $('#buscar').click(function(e){
        e.preventDefault();
        var origen = $('#origen').val();
        var url = '{{route('buscar.index')}}'+'/'+origen;
        alert(url);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data){
               console.log(data);
            }
        });
    })   
})