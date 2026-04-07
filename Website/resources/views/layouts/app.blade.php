<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('css/welcome.blade.css') }}?v={{ time() }} ">

    @yield('custom_css')
</head>
<body>
    <nav>
        <a href="{{ route('welcome') }}" class="home">首頁</a>

        <a href="{{ route('introduction') }}">自我介紹</a>
        <a href="{{ route('socialMedia') }}">社群平台</a>
        <a href="{{ route('gameAchievements') }}">遊戲成就</a>
        <a href="{{ route('goals') }}">未來目標</a>
        <a href="{{ route('contact') }}">聯絡方式</a>
    </nav>

    <div class="content">
        <div class="background-wrapper">
            <div class="floating-card">
                
                @yield('content')
                
            </div>
        </div>
    </div>

</body>
</html>