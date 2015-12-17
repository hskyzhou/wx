<html>

<head>

    <script src="{{asset('admin/vendor/jQuery/jQuery-2.1.4.min.js')}}"></script>

</head>
<body>

<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password" id="password">
    </div>
    @include('auth.code')
    <div>
        <input type="checkbox" name="remember"> Remember Me
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>

</body>
</html>