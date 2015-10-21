
@if($status)
	<h3>角色: {{$role->name}}</h3>
	<ul class="list-group">
		@foreach($role_permissions as $role_permission)
			<li class="list-group-item list-group-item-default">{{$role_permission->slug}}--{{$role_permission->name}}--{{$role_permission->description}}</li>		
		@endforeach
	</ul>
@else
	<p>{{$msg}}</p>
@endif