@if(isset($moduleConfig['has_child']) && $moduleConfig['has_child'])
	<ul class="nav sub-nav rtl txt_right yekan">
		@foreach($moduleConfig['children'] as $chilItems)
			@can($chilItems['gate'])
				<li
						@if($chilItems['route']!=null && url()->current() == route($chilItems['route'])) class="active" @endif
				><a href="{{ ($chilItems['route']!= null ? route($chilItems['route']) : '#') }}"
				    @if(isset($chilItems['has_child']) && $chilItems['has_child']) class="accordion-toggle" @endif>
						<span class="{{ $chilItems['icon'] }}"></span> <span
								class="sidebar-title yekan">{{ $chilItems['title'] }}</span>
						@if(isset($chilItems['has_child']) && $chilItems['has_child'])<span class="caret"></span>@endif
					</a>
					@include('admin.layout.sidebar_item',['modlue'=>$modlue,'moduleConfig' => $chilItems])
				</li>
			@endcan
		@endforeach
	</ul>
@endif