@include('layouts.head')
@include('layouts.menu_tesoreria')
<!-- BARRA OPCIONES CRUD -->
@if ((isset($pagina['tiene_menu'])) && ($pagina['tiene_menu']))
	{{--@include('tesoreria.partials.crud_btn')--}}
	@include('layouts.body')
@else
	@include('layouts.body_sin_option')
@endif
<!-- FIN BARRA OPCIONES CRUD -->
@yield('contenido', 'seccion contenido')

@include('layouts.footer')
@include('layouts.scripts')
<!-- SCRIPTS -->
@yield('scripts_modulo')
@yield('scripts_seccion')
<!-- FIN SCRIPTS -->
@include('layouts.fin_html')
