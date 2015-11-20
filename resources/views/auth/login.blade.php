<html>

<head>

    <script type="text/javascript"  src="{{asset('jquery/jquery.1.11.2.min.js')}}"></script>

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