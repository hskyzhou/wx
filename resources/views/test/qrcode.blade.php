<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
		{!! QrCode::size(100)->generate(Request::url()); !!}
</body>
</html>