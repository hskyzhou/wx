<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<div>
		<span>{{$user['name']}}</span>
		<a href="/auth/logout">退出</a>
	</div>
	
	<p>{{$msg}}</p>
	<div>
		<h1>我拥有的权限</h1>
		<div>
			{{$myPermission or ''}}
		</div>
	</div>
	
	@if($role)
	<div>
		<h1>用户分配权限; 角色分配权限</h1>
		<table border="1">
			<tr>
				<th>用户名</th>
				<th>邮箱</th>
				<th>操作</th>
			</tr>

			@forelse($users as $user)
			<tr>
				<td>{{$user['name']}}</td>
				<td>{{$user['email']}}</td>
				<td>
					<a href="/per/user/{{$user['id']}}">分配用户权限</a>
					<a href="/per/role/{{$user['id']}}">分配用户角色</a>
				</td>
			</tr>
			@empty
			<tr>
				<td>没有用户</td>
			</tr>
			@endforelse
		</table>
	</div>

	<div>
		<h1>添加用户</h1>
	</div>

	<div>
		<h1>添加角色</h1>
	</div>

	<div>
		<h1>添加权限</h1>
	</div>
	@endif
</body>
</html>