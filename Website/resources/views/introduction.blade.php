@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            自我介紹
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/introduction.blade.css') }}">
@endsection

@section('content')
    <h2>
        Biggest Shark
    </h2>
    <p>
        可以叫我shark，或是鯊魚
    </p>

    <h2>
        遊戲
    </h2>
    <p>
        我只要放假就是在打遊戲
    </p>
    <p>
        通常是一些單機遊戲
    </p>
    <p>
        如果需要遊戲推薦可以dc私訊
    </p>

    <h2>
        動畫
    </h2>
    <p>
        目前只看百合
    </p>
    <p>
        包括漫畫和小說
    </p>
    <p>
        下面連結可以看我有什麼書
    </p>
    <a href="https://docs.google.com/spreadsheets/d/1M0vkh6yoU0GnUAtRIjRUhoDCVlRwu_ctgd4bbNQo26Q/edit?gid=0#gid=0" class="sheet-card" target="_blank" rel="noopener noreferrer">
        <div class="sheet-icon">
            📚
        </div>
        <div class="sheet-info">
            <span class="sheet-title">Biggest Shark的圖書館</span>
            <span class="sheet-desc">包含同人誌</span>
        </div>
    </a>
@endsection