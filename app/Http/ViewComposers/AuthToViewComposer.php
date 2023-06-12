<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AuthToViewComposer {

	public function compose(View $view) {
		$autenticado = [];
		if (Auth::check()){
			$autIni = Auth::user();
			$autenticado = $autIni->toArray();
			$autenticado['roles'] = Auth::user()->getAllRol();
			$autenticado['grupos'] = Auth::user()->getAllGrupo();
	
		}
		$view->with('auth_user', json_encode($autenticado));
	}
}
