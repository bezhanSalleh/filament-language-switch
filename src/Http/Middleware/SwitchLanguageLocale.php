<?php
	
	namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;
	
	use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
	use Closure;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class SwitchLanguageLocale
	{
		public function handle(Request $request , Closure $next): Response|RedirectResponse
		{
			$locale = session()->get('locale')
				?? $request->get('locale')
				?? $request->cookie('filament_language_switch_locale')
				?? config('app.locale' , 'en')
				?? $this->getBrowserLocale($request);
			if (in_array($locale , LanguageSwitch::make()->getLocales())) {
				app()->setLocale($locale);
			}
			
			return $next($request);
		}
		
		private function getBrowserLocale(Request $request): ?string
		{
			$appLocales = LanguageSwitch::make()->getLocales();
			
			$userLocales = preg_split('/[,;]/' , $request->server('HTTP_ACCEPT_LANGUAGE'));
			
			foreach ($userLocales as $locale) {
				if (in_array($locale , $appLocales)) {
					return $locale;
				}
			}
			
			return null;
		}
	}
