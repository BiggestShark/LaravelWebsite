<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http; // 記得要引入 Http 類別

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
    return view('gameAchievements');
})->name('gameAchievements');

Route::get('/goals', function () {
    return view('goals');
})->name('goals');

Route::get('/contact', function () {
    return view('contact');
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