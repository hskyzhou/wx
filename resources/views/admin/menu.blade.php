@foreach($menus as $menu)
	@if(!isset($menu['son']) && empty($menu['son']))
		@if(isset($activemenus) && in_array($menu['id'], $activemenus))
			<li class="active"><a href="{{url($menu['url'])}}"><i class="fa fa-circle-o text-aqua"></i> <span>{{$menu['name']}}</span></a></li>
		@else
			<li><a href="{{url($menu['url'])}}"><i class="fa fa-circle-o text-aqua"></i> <span>{{$menu['name']}}</span></a></li>
		@endif
	@else
		@if(isset($activemenus) && in_array($menu['id'], $activemenus)) 
			<li class="treeview active">
		@else 
			<li class="treeview">
		@endif
		  	<a href="#">
		    	<i class="fa fa-dashboard"></i> <span>{{$menu['name']}}</span> <i class="fa fa-angle-left pull-right"></i>
		  	</a>
		  	<ul class="treeview-menu">
		  		@if(isset($menu['son']))
			    	@foreach($menu['son'] as $sub_menu)
			    		@if(isset($activemenus) && in_array($sub_menu['id'], $activemenus)) 
			    			<li claas="active">
			    		@else 
			    			<li>
			    		@endif
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