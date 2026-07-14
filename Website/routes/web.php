<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http; // 記得要引入 Http 類別
use Illuminate\Support\Facades\Cache; // 引入快取類別
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/socialMedia', function () {
    return view('socialMedia');
})->name('socialMedia');

Route::get('/introduction', function () {
    return view('introduction');
})->name('introduction');

Route::get('/gameAchievements', function () {

    $clientId = env('OSU_CLIENT_ID');
    $clientSecret = env('OSU_CLIENT_SECRET');
    $osuUserId = env('OSU_USER_ID');

    // 1. 取得 osu! Token
    $token = Cache::remember('osu_token', 86400, function () use ($clientId, $clientSecret) {
        $response = Http::asForm()->post('https://osu.ppy.sh/oauth/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'public',
        ]);
        return $response->json('access_token');
    });

    // 2. 取得 osu! 個人資料。快取過期時先回傳舊資料，再於回應後更新。
    $osuData = Cache::flexible("osu_data_v1_{$osuUserId}", [1800, 86400], function () use ($token, $osuUserId) {
        $data = [
            'standard' => null,
            'taiko' => null,
            'standard_highest_pp' => null,
            'taiko_highest_pp' => null,
        ];

        if (! $token) {
            return $data;
        }

        $responses = Http::pool(fn (Pool $pool) => [
            'standard' => $pool->as('standard')->withToken($token)->timeout(10)
                ->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}"),
            'taiko' => $pool->as('taiko')->withToken($token)->timeout(10)
                ->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/taiko"),
            'standard_best' => $pool->as('standard_best')->withToken($token)->timeout(10)
                ->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/scores/best", [
                    'limit' => 1,
                    'mode' => 'osu',
                ]),
            'taiko_best' => $pool->as('taiko_best')->withToken($token)->timeout(10)
                ->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/scores/best", [
                    'limit' => 1,
                    'mode' => 'taiko',
                ]),
        ]);

        if (($responses['standard'] ?? null) instanceof Response && $responses['standard']->successful()) {
            $data['standard'] = $responses['standard']->json();
        }

        if (($responses['taiko'] ?? null) instanceof Response && $responses['taiko']->successful()) {
            $data['taiko'] = $responses['taiko']->json();
        }

        if (($responses['standard_best'] ?? null) instanceof Response && $responses['standard_best']->successful()) {
            $data['standard_highest_pp'] = $responses['standard_best']->json('0.pp');
        }

        if (($responses['taiko_best'] ?? null) instanceof Response && $responses['taiko_best']->successful()) {
            $data['taiko_highest_pp'] = $responses['taiko_best']->json('0.pp');
        }

        return $data;
    });

    $osuSTDData = $osuData['standard'];
    $osuTaikoData = $osuData['taiko'];
    $osuHighestPP = $osuData['standard_highest_pp'];
    $osuTaikoHighestPP = $osuData['taiko_highest_pp'];

    $steamKey = env('STEAM_API_KEY');
    $steamId = env('STEAM_ID');

    // 3. 取得 Steam 資料並快取
    $steamData = Cache::flexible("steam_data_v3_{$steamId}", [3600, 86400], function () use ($steamKey, $steamId) {
        
        $summaryRes = Http::get("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/", [
            'key' => $steamKey,
            'steamids' => $steamId
        ]);
        $player = $summaryRes->json('response.players.0');

        $ownedRes = Http::get("https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/", [
            'key' => $steamKey,
            'steamid' => $steamId,
            'include_appinfo' => true, 
            'include_played_free_games' => true
        ]);
        
        $gameCount = $ownedRes->json('response.game_count') ?? 0;
        $ownedGames = $ownedRes->json('response.games') ?? [];
        $top5Games = collect($ownedGames)->sortByDesc('playtime_forever')->take(5)->values()->all();

        $perfectGames = Cache::flexible("steam_perfect_games_v2_{$steamId}", [21600, 604800], function () use ($steamKey, $steamId, $ownedGames) {
            $achievementGames = collect($ownedGames)
                ->filter(fn ($game) => ($game['playtime_forever'] ?? 0) > 0 && ($game['has_community_visible_stats'] ?? false))
                ->values();
            $perfectGames = collect();

            $responses = Http::pool(function (Pool $pool) use ($achievementGames, $steamKey, $steamId) {
                foreach ($achievementGames as $game) {
                    $pool->as((string) $game['appid'])->timeout(10)->get(
                        'https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/',
                        [
                            'key' => $steamKey,
                            'steamid' => $steamId,
                            'appid' => $game['appid'],
                        ]
                    );
                }
            }, concurrency: 20);

            foreach ($achievementGames as $game) {
                $response = $responses[(string) $game['appid']] ?? null;

                if (! $response instanceof Response || ! $response->successful()) {
                    continue;
                }

                $achievements = $response->json('playerstats.achievements') ?? [];
                $isPerfect = count($achievements) > 0
                    && collect($achievements)->every(fn ($achievement) => ($achievement['achieved'] ?? 0) === 1);

                if ($isPerfect) {
                    $perfectGames->push([
                        'appid' => $game['appid'],
                        'name' => $game['name'],
                        'achievement_count' => count($achievements),
                        'playtime_forever' => $game['playtime_forever'],
                    ]);
                }
            }

            return $perfectGames->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
        });

        $recentRes = Http::get("https://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/", [
            'key' => $steamKey,
            'steamid' => $steamId,
            'count' => 5
        ]);
        $recentGames = $recentRes->json('response.games') ?? [];

        // 快取區塊僅回傳資料陣列
        return [
            'profile' => $player,
            'total_games' => $gameCount,
            'top_5_games' => $top5Games,
            'recent_games' => $recentGames,
            'perfect_games' => $perfectGames,
        ];
    });

    // 4. 外層 Route 統一回傳視圖與所有變數
    return view('gameAchievements', [
        'osuSTDData' => $osuSTDData,
        'osuTaikoData' => $osuTaikoData,
        'osuHighestPP' => $osuHighestPP,
        'osuTaikoHighestPP' => $osuTaikoHighestPP,
        'steamData' => $steamData,
    ]);

})->name('gameAchievements');

Route::get('/goals', function () {
    return view('goals');
})->name('goals');

Route::get('/contacts', function () {
    return view('contacts');
})->name('contact');

Route::get('/socialMedia', function () {
    // 1. 把抓資料的邏輯寫在這個路由裡面
    $channelId = 'UC87FWHuvKCnsK4v8VdITKyw';
    $rssUrl = "https://www.youtube.com/feeds/videos.xml?channel_id={$channelId}";
    $latestVideo = null;

    try {
        $response = Http::get($rssUrl);
        if ($response->successful()) {
            $xml = simplexml_load_string($response->body());
            if (!empty($xml->entry)) {
                $firstVideo = $xml->entry[0];
                $latestVideo = [
                    'id' => str_replace('yt:video:', '', (string) $firstVideo->id),
                    'title' => (string) $firstVideo->title,
                ];
            }
        }
    } catch (\Exception $e) {}

    // 2. 關鍵在這裡！必須把 $latestVideo 打包傳給 youtube.blade.php
    return view('socialMedia', [
        'latestVideo' => $latestVideo
    ]);
})->name('socialMedia'); // 注意這裡的名字要跟上面一致，因為我們在 app.blade.php 的導覽列裡面是用 route('socialMedia') 來產生網址的

// 下面保留你剛剛建立的 /about, /games 等其他路由...
