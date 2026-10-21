<ul class="nav sidebar-menu">
	<li>
		<a href="{{ url('admin/dashboard') }}"><span class="fa fa-dashboard"></span> <span
					class="sidebar-title yekan">داشبورد</span></a>
	</li>
	@php
		$menu = config('menu');
		$modlues = \Module::allEnabled();
	@endphp
	@foreach($menu['Menu'] as $menuItem)
		@can($menuItem['gate'])
			<li
					@if($menuItem['route']!=null && url()->current() == route($menuItem['route'])) class="active" @endif
			><a href="{{ ($menuItem['route']!= null ? route($menuItem['route']) : '#') }}"
			    @if(isset($menuItem['has_child']) && $menuItem['has_child']) class="accordion-toggle" @endif><span
							class="{{ $menuItem['icon'] }}"></span> <span
							class="sidebar-title yekan">{{ $menuItem['title'] }}</span>@if(isset($menuItem['has_child']) && $menuItem['has_child'])
						<span class="caret"></span>@endif</a>
				@include('admin.layout.sidebar_item',['modlue'=>null,'moduleConfig' => $menuItem])
			</li>
		@endcan
	@endforeach
	@foreach($menu['Cat'] as $cat)
		<li><a href="#"
		       class="accordion-toggle"><span class="{{ $cat['icon'] }}"></span> <span
						class="sidebar-title yekan">{{ $cat['title'] }}</span><span
						class="caret"></span></a>
			<ul class="nav sub-nav rtl txt_right yekan">
				@foreach($cat['modules'] as $module)
					@php
						$modlue = \Module::find($module);
						if(\Module::has($module)){
							$moduleConfig = config($modlue->getLowerName().".Menu") ?? array();
						}else{
							$moduleConfig = array();
						}
					@endphp
					@if(sizeof($moduleConfig) >= 1 && isset($modlues[$module]))
						@can($moduleConfig['gate'])
							<li><a href="{{ ($moduleConfig['route']!= null ? route($moduleConfig['route']) : '#') }}"
							       class="accordion-toggle"><span class="{{ $moduleConfig['icon'] }}"></span> <span
											class="sidebar-title yekan">{{ $moduleConfig['title'] }}</span><span
											class="caret"></span></a>
								@include('admin.layout.sidebar_item',['modlue'=>$modlue,'moduleConfig' => $moduleConfig])
							</li>
						@endcan
					@endif
					@php
						unset($modlues[$module]);
					@endphp
				@endforeach
			</ul>
		</li>
	@endforeach
	@foreach($modlues as $modlue)
		@php
			$moduleConfig = config($modlue->getLowerName().".Menu") ?? array();
		@endphp
		@if(sizeof($moduleConfig) >= 1)
			@can($moduleConfig['gate'])
				<li><a href="{{ ($moduleConfig['route']!= null ? route($moduleConfig['route']) : '#') }}"
					   @if(isset($moduleConfig['has_child']) && $moduleConfig['has_child']) class="accordion-toggle" @endif><span class="{{ $moduleConfig['icon'] }}"></span> <span
								class="sidebar-title yekan">{{ $moduleConfig['title'] }}</span>
						@if(isset($moduleConfig['has_child']) && $moduleConfig['has_child']) <span class="caret" ></span>@endif</a>
					@include('admin.layout.sidebar_item',['modlue'=>$modlue,'moduleConfig' => $moduleConfig])
				</li>
			@endcan
		@endif
	@endforeach
	<li>
		<a href="{{ route('logout') }}">
			<span class="fa fa-sign-out"></span>
			<span class="sidebar-title">
                خروج
            </span>
		</a>
	</li>
</ul>