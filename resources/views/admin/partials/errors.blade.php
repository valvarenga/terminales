@if($errors->any())
<div class="alert alert-danger" role="alert"><strong>Revisa los datos del formulario.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
