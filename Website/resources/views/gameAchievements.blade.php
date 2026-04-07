@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            遊戲成就
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/gameAchievements.blade.css') }}?v={{ time() }}">
@endsection

@section('content')
    <details>
        <summary>
            <h2>
                osu!
            </h2>
        </summary>
        @if($osuSTDData)
            {{-- 🌟 1. 整個資料卡片的容器 (長方形、圓角) --}}
            <div class="osu-card">
                
                {{-- 🌟 2. 模糊的背景圖 (使用 cover_url) --}}
                <div class="osu-cover-bg" style="background-image: url('{{ $osuSTDData['cover_url'] }}');"></div>
                
                
                {{-- 🌟 3. 前方真實的內容 (加上半透明遮罩，讓文字更清晰) --}}
                <div class="osu-content-overlay">
                    
                    {{-- 🌟 4. 左上角的圓角正方形大頭貼 (使用 avatar_url) --}}
                    <a href="https://osu.ppy.sh/users/{{ $osuSTDData['id'] }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $osuSTDData['avatar_url'] }}" alt="Avatar" class="osu-avatar">
                    </a>
                    
                    {{-- 這裡預留位置，等一下放玩家名稱和數據 --}}
                    <div class="osu-player-info">
                        <div class="osu-player-info">
                            {{-- 玩家 ID 與國旗 --}}
                            <div class="osu-header">
                                <h2 class="osu-username">{{ $osuSTDData['username'] }}</h2>
                                {{-- osu! 官方的國旗小圖示 --}}
                                <img src="https://osu.ppy.sh/assets/images/flags/{{ $osuSTDData['country_code'] }}.png" alt="{{ $osuSTDData['country_code'] }}" class="osu-flag">
                            </div>

                            {{-- 數據方塊區 (使用 Grid 排版) --}}
                            <div class="osu-stats-grid">
                                <div class="osu-stat-box">
                                    <span class="osu-label">Global Rank</span>
                                    <span class="osu-value">#{{ number_format($osuSTDData['statistics']['global_rank'] ?? 0) }}</span>
                                </div>
                                
                                <div class="osu-stat-box">
                                    <span class="osu-label">Performance Points (PP)</span>
                                    <span class="osu-value highlight">{{ number_format($osuSTDData['statistics']['pp'] ?? 0) }}</span>
                                </div>
                                
                                <div class="osu-stat-box">
                                    <span class="osu-label">Accuracy</span>
                                    <span class="osu-value">{{ round($osuSTDData['statistics']['hit_accuracy'] ?? 0, 2) }}%</span>
                                </div>
                                
                                <div class="osu-stat-box">
                                    <span class="osu-label">Total Play Counts</span>
                                    <span class="osu-value">{{ number_format($osuSTDData['statistics']['play_count'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @else
            <p>正在努力載入 osu! 伺服器資料中...</p>
        @endif
        <p>
            2019年開始玩，2020上半年轉板子
        </p>
    </details>

    <details>
        <summary>
            <h2>
                Steam
            </h2>
        </summary>
        @if(isset($steamData))
        <div class="steam-card">
            <div class="steam-header">
                <img src="{{ $steamData['profile']['avatarfull'] }}" alt="Steam Avatar" class="steam-avatar">
                <div class="steam-info">
                    <h2>{{ $steamData['profile']['personaname'] }}</h2>
                    <p>狀態: {{ $steamData['profile']['personastate'] == 1 ? '線上' : '離線/其他' }}</p>
                    <p>總遊戲數: {{ $steamData['total_games'] }}</p>
                </div>
            </div>

            <div class="steam-section">
                <h3>最近遊玩</h3>
                <div class="steam-games-grid">
                    @foreach($steamData['recent_games'] as $game)
                    <div class="steam-game-item">
                        <img src="https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/{{ $game['appid'] }}/capsule_231x87.jpg" alt="{{ $game['name'] }}">
                        <div class="game-details">
                            <span class="game-name">{{ $game['name'] }}</span>
                            <span class="game-time">過去兩週: {{ round($game['playtime_2weeks'] / 60, 1) }} 小時</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="steam-section">
                <h3>最高時數前五名</h3>
                <div class="steam-games-grid">
                    @foreach($steamData['top_5_games'] as $game)
                    <div class="steam-game-item">
                        <img src="https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/{{ $game['appid'] }}/capsule_231x87.jpg" alt="{{ $game['name'] }}">
                        <div class="game-details">
                            <span class="game-name">{{ $game['name'] }}</span>
                            <span class="game-time">總時數: {{ round($game['playtime_forever'] / 60, 1) }} 小時</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </details>
@endsection