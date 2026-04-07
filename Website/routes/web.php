<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http; // 記得要引入 Http 類別
use Illuminate\Support\Facades\Cache; // 引入快取類別

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

    // 2. 取得 osu! 個人資料
    $osuSTDData = null;
    $osuTaikoData = null;
    $osuSTDRecentTop5Scores = null;
    $osuTaikoRecentTop5Scores = null;
    if ($token) {
        $response = Http::withToken($token)->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}");
        if ($response->successful()) {
            $osuSTDData = $response->json();
        }

        $response = Http::withToken($token)->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/taiko");
        if ($response->successful()) {
            $osuTaikoData = $response->json();
        }

        $response = Http::withToken($token)->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/scores/best", [
            'limit' => 100,
            'mode' => 'std',
        ]);

        $osuSTDtop100Scores = $response->collect();

        $osuSTDRecentTop5Scores = $osuSTDtop100Scores->sortByDesc('create_at')
                                                     ->values()
                                                     ->take(5);

        $response = Http::withToken($token)->get("https://osu.ppy.sh/api/v2/users/{$osuUserId}/taiko/scores/best", [
            'limit' => 100,
            'mode' => 'taiko',
        ]);

        $osuTaikotop100Scores = $response->collect();

        $osuTaikoRecentTop5Scores = $osuTaikotop100Scores->sortByDesc('create_at')
                                                          ->values()
                                                          ->take(5);
        
    }  

    $steamKey = env('STEAM_API_KEY');
    $steamId = env('STEAM_ID');

    // 3. 取得 Steam 資料並快取
    $steamData = Cache::remember("steam_data_{$steamId}", 3600, function () use ($steamKey, $steamId) {
        
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
        ];
    });

    // 4. 外層 Route 統一回傳視圖與所有變數
    return view('gameAchievements', [
        'osuSTDData' => $osuSTDData,
        'osuTaikoData' => $osuTaikoData,
        'osuSTDRecentTop5Scores' => $osuSTDRecentTop5Scores,
        '$osuTaikoRecentTop5Scores' => $osuTaikoRecentTop5Scores,
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