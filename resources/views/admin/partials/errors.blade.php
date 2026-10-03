@if($errors->any())
<div class="alert alert-danger" role="alert" tabindex="-1" data-error-summary><strong>Revisa los datos del formulario.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
