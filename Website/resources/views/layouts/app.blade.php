<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'welcome')</title>
    
    <link rel="stylesheet" href="{{ asset('css/welcome.blade.css') }}">

    @yield('custom_css')
</head>
<body>

    

    <div class="content">
        @yield('content')
    </div>

    <nav>
        <a href="{{ route('introduction') }}">自我介紹</a>
        <a href="{{ route('socialMedia') }}">社群平台</a>
        <a href="{{ route('gameAchievements') }}">遊戲成就</a>
        <a href="{{ route('goals') }}">未來目標</a>
        <a href="{{ route('contact') }}">聯絡方式</a>
    </nav>

</body>
</html>