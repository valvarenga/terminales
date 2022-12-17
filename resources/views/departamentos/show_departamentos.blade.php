@extends('layouts.plantilla')

@section('tittle','Departamentos')

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr class="table table-primary">
                <th >Nombre</th>
                <th>Imagen</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        @foreach($departamentos as $departamento)
        <tbody>       
            <td>{{$departamento->nombre}}</td>
            <td> <img src="{{$departamento->url}}" alt="150" width="150"></td>
            <td>
                //href="{{route('departamento.update',$departamento->id)}}"
                <button type="button" class="btn btn-success" id="edit" data-toggle="modal" data-target="#editModal"> Editar</button> 
            </td>
            <td>
                <form action="{{route('departamento.destroy',$departamento->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger" id="delete"> Eliminar</button>
                </form>
            </td>
         </tbody>
    <!-- MODAL EDITAR -->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Departamento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
                     <form action="" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="nombre">Nombre del departamento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $departamento->nombre }}">
                        </div>
                        <div class="form-group">
                            <label for="imagen">Suba la imagen</label>
                            <input type="file" class="form-control-file mt-2" id="imagen" name="imagen">
                        </div>
                    </form>              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="hidden" class="btn btn-primary" id="update">Actualizar</button>
            </div>
          </div>
        </div>
    </div>

    <!-- FIN DEL MODAL -->
         @endforeach
    </table>
    
    <div class="d-grid gap-2 col-6 mx-auto">
        <a href="{{route('ruta.index')}}"><button class="btn btn-primary" type="button">Regresar</button></a>
    </div>


@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#edit').click(function () {
            $('#editModal').modal('show')
        }); 
        
        $('#update').click(function (e) {
            preventDefault(e);
            $.ajax({
                route: 
            })
            
        });

     });
</script>

@endsection


