@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            遊戲成就
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/gameAchievements.blade.css') }}">
@endsection

@section('content')
    <details class="osu">
        <summary>
            <h2>
                osu!
            </h2>
        </summary>
        <a href="https://osu.ppy.sh/users/13997282">Profile</a>
        <p>
            2019年開始玩，2020上半年轉板子，原本都打跳圖，2022開始練切指，現在主要玩些gimmick、alt、還有一些好玩的圖
        </p>
    </details>

    <details class="sekiro">
        <summary>
            <h2>
                隻狼
            </h2>
        </summary>
        <p>
            2019年買的，算是我的第一款破完的soul like game，看qttsix打破戒僧和一心覺得很帥。
        </p>
        <p>
            之後玩著玩著，開始研究glitchless路線，最後自己慢慢修，2025年跑出了滿意的sub 90 mins成績。
        </p>
    </details>

    <details class="elden-ring">
        <summary>
            <h2>
                艾爾登法環
            </h2>
        </summary>
        <p>
            這遊戲是預購的，大一直接在宿舍開打，
        </p>
        <p>
            之後玩著玩著，開始研究glitchless路線，最後自己慢慢修，2025年跑出了滿意的sub 90 mins成績。
        </p>
    </details>
@endsection