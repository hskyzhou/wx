@foreach($menus as $menu)
	@if(!isset($menu['son']) && empty($menu['son']))
		<li class="{{Active::pattern($menu['url'])}}"><a href="{{url($menu['url'])}}"><i class="fa fa-circle-o text-aqua"></i> <span>{{$menu['name']}}</span></a></li>
	@else
		<li class="{{Active::pattern($menu['url'] . '*/*')}}">
		  	<a href="#">
		    	<i class="fa fa-dashboard"></i> <span>{{$menu['name']}}</span> <i class="fa fa-angle-left pull-right"></i>
		  	</a>
		  	<ul class="treeview-menu">
		  		@if(isset($menu['son']))
			    	@foreach($menu['son'] as $sub_menu)
			    		<li class="{{Active::pattern($sub_menu['url'])}}">
				    		<a href="{{url($sub_menu['url'])}}">
				    			<i class="fa fa-circle-o"></i>{{$sub_menu['name']}}
				    		</a>
				    	</li>
			    	@endforeach
		    	@endif
		  	</ul>
		</li>
	@endif
@endforeach