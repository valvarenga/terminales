@extends('layouts.plantilla')


@section('content')

<button type="button" class="btn btn-success" id="edit" data-toggle="modal" data-target="#ModalEditar"> Editar</button>
<br>
<form action="#" method="POST">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-danger"> Eliminar</button>
</form>

<ul class="list-group">
    
   <strong> <li class="list-group-item active" aria-current="true">{{$departamentos->nombre}}</li></strong>
  
</ul>

<!-- Modal de editar-->
<div class="modal fade"  role="dialog" id="ModalEditar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar departamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="nombre">Nombre del departamento</label>
            <input type="text" name="nombre" id="nombre_departamento" class="form-control" value="{{$departamentos->nombre}}">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="actualizar">Actualizar</button>
    </div>
  </div>
</div>

<br>
<div class="d-grid gap-2 col-6 mx-auto">
    <a href="{{route('show_terminal')}}"><button class="btn btn-primary" type="button">Regresar</button></a>
  </div>

@endsection

@section('scripts')

    <script>
      var ruta = '{{route('departamento.update',)}}'+'/'+{{$departamentos->id}};
      
      
      //var nombre = document.getElementById('nombre_departamento').val();
      $.ajaxSetup({

          headers: {

              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

      });
      $(document).ready(function(){
            $('#edit').click(function(){
                $('#ModalEditar').modal('show');
            });

            if($('#nombre_departamento').on('change'), '&&', $('#actualizar').click(function(){
              var nombre = $('#nombre_departamento').val();
              
                if(nombre){
                console.log(nombre);
                $.ajax({
                    url: ruta,
                    //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'put',
                    dataType: 'json',
                    data: {
                        
                        'nombre': nombre
                    },
                    success: function(data){
                        console.log(data);
                        $('#ModalEditar').modal('hide');
                    }
                });
              }               
              else{
                console.log("Error");
              
              }
            }));//Fin del if
            else{
              console.log('No se pudo realizar el cambio');
            }
 
        });

      
    </script>
@endsection