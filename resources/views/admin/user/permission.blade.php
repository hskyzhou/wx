
@if($status)
	<h3>用户: {{$user->name}}</h3>
	<ul class="list-group">
		@foreach($user_permissions as $user_permission)
			<li class="list-group-item list-group-item-default">{{$user_permission->slug}}--{{$user_permission->name}}--{{$user_permission->description}}</li>		
		@endforeach
	</ul>
@else
	<p>{{$msg}}</p>
@endif