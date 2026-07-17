@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            遊戲成就
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/gameAchievements.blade.css') }}?v={{ filemtime(public_path('css/gameAchievements.blade.css')) }}">
@endsection

@section('content')
    <details>
        <summary>
            <h2>
                osu!
            </h2>
        </summary>
        @if($osuSTDData)
            <h3 class="osu-mode-title">
                osu!standard
            </h3>

            {{-- 🌟 1. 整個資料卡片的容器 (長方形、圓角) --}}
            <div class="osu-card">
                
                {{-- 🌟 2. 模糊的背景圖 (使用 cover_url) --}}
                <div class="osu-cover-bg" style="background-image: url('{{ $osuSTDData['cover_url'] }}');"></div>
                
                
                {{-- 🌟 3. 前方真實的內容 (加上半透明遮罩，讓文字更清晰) --}}
                <div class="osu-content-overlay">
                    
                    {{-- 🌟 4. 左上角的圓角正方形大頭貼 (使用 avatar_url) --}}
                    <a href="https://osu.ppy.sh/users/{{ $osuSTDData['id'] }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $osuSTDData['avatar_url'] }}" alt="Avatar" class="osu-avatar" loading="lazy" decoding="async">
                    </a>
                    
                    {{-- 這裡預留位置，等一下放玩家名稱和數據 --}}
                    <div class="osu-player-info">
                        <div class="osu-player-info">
                            {{-- 玩家 ID 與國旗 --}}
                            <div class="osu-header">
                                <h2 class="osu-username">{{ $osuSTDData['username'] }}</h2>
                                {{-- 玩家所屬國家的國旗小圖示 --}}
                                <img
                                    src="https://flagcdn.com/40x30/{{ strtolower($osuSTDData['country_code']) }}.png"
                                    alt="{{ $osuSTDData['country_code'] }}"
                                    class="osu-flag"
                                    loading="lazy"
                                    decoding="async">
                                @if(!empty($osuSTDData['team']['flag_url']))
                                    <img
                                        src="{{ $osuSTDData['team']['flag_url'] }}"
                                        alt="{{ $osuSTDData['team']['name'] ?? 'osu! team' }}"
                                        title="{{ $osuSTDData['team']['short_name'] ?? $osuSTDData['team']['name'] ?? '' }}"
                                        class="osu-flag"
                                        loading="lazy"
                                        decoding="async">
                                @endif
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

                                <div class="osu-stat-box">
                                    <span class="osu-label">Play Time</span>
                                    <span class="osu-value">{{ number_format(floor(($osuSTDData['statistics']['play_time'] ?? 0) / 3600)) }} 小時</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Best Performance</span>
                                    <span class="osu-value highlight">
                                        {{ $osuHighestPP !== null ? number_format($osuHighestPP, 2) . ' PP' : '暫無資料' }}
                                    </span>
                                </div>

                                <div class="osu-stat-box wide">
                                    <span class="osu-label">Account Created</span>
                                    <span class="osu-value">
                                        {{ !empty($osuSTDData['join_date']) ? \Illuminate\Support\Carbon::parse($osuSTDData['join_date'])->format('Y/m/d') : '暫無資料' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @else
            <p>
                正在努力載入 osu!standard 伺服器資料中...
            </p>
        @endif

        @if($osuTaikoData)
            <h3 class="osu-mode-title">
                osu!taiko
            </h3>

            <div class="osu-card">
                <div class="osu-cover-bg" style="background-image: url('{{ $osuTaikoData['cover_url'] }}');"></div>

                <div class="osu-content-overlay">
                    <a href="https://osu.ppy.sh/users/{{ $osuTaikoData['id'] }}/taiko" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $osuTaikoData['avatar_url'] }}" alt="Avatar" class="osu-avatar" loading="lazy" decoding="async">
                    </a>

                    <div class="osu-player-info">
                        <div class="osu-player-info">
                            <div class="osu-header">
                                <h2 class="osu-username">{{ $osuTaikoData['username'] }}</h2>
                                <img
                                    src="https://flagcdn.com/40x30/{{ strtolower($osuTaikoData['country_code']) }}.png"
                                    alt="{{ $osuTaikoData['country_code'] }}"
                                    class="osu-flag"
                                    loading="lazy"
                                    decoding="async">
                                @if(!empty($osuTaikoData['team']['flag_url']))
                                    <img
                                        src="{{ $osuTaikoData['team']['flag_url'] }}"
                                        alt="{{ $osuTaikoData['team']['name'] ?? 'osu! team' }}"
                                        title="{{ $osuTaikoData['team']['short_name'] ?? $osuTaikoData['team']['name'] ?? '' }}"
                                        class="osu-flag"
                                        loading="lazy"
                                        decoding="async">
                                @endif
                            </div>

                            <div class="osu-stats-grid">
                                <div class="osu-stat-box">
                                    <span class="osu-label">Global Rank</span>
                                    <span class="osu-value">#{{ number_format($osuTaikoData['statistics']['global_rank'] ?? 0) }}</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Performance Points (PP)</span>
                                    <span class="osu-value highlight">{{ number_format($osuTaikoData['statistics']['pp'] ?? 0) }}</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Accuracy</span>
                                    <span class="osu-value">{{ round($osuTaikoData['statistics']['hit_accuracy'] ?? 0, 2) }}%</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Total Play Counts</span>
                                    <span class="osu-value">{{ number_format($osuTaikoData['statistics']['play_count'] ?? 0) }}</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Play Time</span>
                                    <span class="osu-value">{{ number_format(floor(($osuTaikoData['statistics']['play_time'] ?? 0) / 3600)) }} 小時</span>
                                </div>

                                <div class="osu-stat-box">
                                    <span class="osu-label">Best Performance</span>
                                    <span class="osu-value highlight">
                                        {{ $osuTaikoHighestPP !== null ? number_format($osuTaikoHighestPP, 2) . ' PP' : '暫無資料' }}
                                    </span>
                                </div>

                                <div class="osu-stat-box wide">
                                    <span class="osu-label">Account Created</span>
                                    <span class="osu-value">
                                        {{ !empty($osuTaikoData['join_date']) ? \Illuminate\Support\Carbon::parse($osuTaikoData['join_date'])->format('Y/m/d') : '暫無資料' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p>
                正在努力載入 osu!taiko 伺服器資料中...
            </p>
        @endif
        <p>
            2019年開始玩，2020上半年轉板子
        </p>
        <p>
            目前隨緣刷pp
        </p>
        <p>
            歡迎mutual
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
                <img src="{{ $steamData['profile']['avatarfull'] }}" alt="Steam Avatar" class="steam-avatar" loading="lazy" decoding="async">
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
                    <a
                        href="https://store.steampowered.com/app/{{ $game['appid'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="steam-game-link">
                        <div class="steam-game-item">
                            <img src="https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/{{ $game['appid'] }}/capsule_231x87.jpg" alt="{{ $game['name'] }}" loading="lazy" decoding="async">
                            <div class="game-details">
                                <span class="game-name">{{ $game['name'] }}</span>
                                <span class="game-time">過去兩週: {{ round($game['playtime_2weeks'] / 60, 1) }} 小時</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="steam-section">
                <h3>最高時數前五名</h3>
                <div class="steam-games-grid">
                    @foreach($steamData['top_5_games'] as $game)
                    <a
                        href="https://store.steampowered.com/app/{{ $game['appid'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="steam-game-link">
                        <div class="steam-game-item">
                            <img src="https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/{{ $game['appid'] }}/capsule_231x87.jpg" alt="{{ $game['name'] }}" loading="lazy" decoding="async">
                            <div class="game-details">
                                <span class="game-name">{{ $game['name'] }}</span>
                                <span class="game-time">總時數: {{ round($game['playtime_forever'] / 60, 1) }} 小時</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="steam-section">
                <h3>全成就遊戲（{{ count($steamData['perfect_games'] ?? []) }}）</h3>
                @if(!empty($steamData['perfect_games']))
                    <div class="steam-games-grid">
                        @foreach($steamData['perfect_games'] as $game)
                        <a
                            href="https://store.steampowered.com/app/{{ $game['appid'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="steam-game-link">
                            <div class="steam-game-item steam-perfect-game">
                                <img src="https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/{{ $game['appid'] }}/capsule_231x87.jpg" alt="{{ $game['name'] }}" loading="lazy" decoding="async">
                                <div class="game-details">
                                    <span class="game-name">{{ $game['name'] }}</span>
                                    <span class="game-time">已完成 {{ $game['achievement_count'] }} 個成就</span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <p class="steam-empty-message">目前沒有可顯示的全成就遊戲。</p>
                @endif
            </div>
        </div>
        @endif
    </details>
@endsection
