@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            社群平台
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/socialMedia.blade.css') }}">
@endsection

@section('content')
    <div id="latest-video">
        <h2>Youtube最新影片</h2>
        
        {{-- 檢查有沒有成功抓到影片資料 --}}
        @if($latestVideo)
            <div class="video">
                {{-- 把抓到的標題印出來 --}}
                <h3>
                    {{ $latestVideo['title'] }}
                </h3>
                
                {{-- 將影片 ID 塞進 iframe 的網址裡 --}}
                <iframe 
                    class="styled-iframe" 
                    width="853" 
                    height="480" 
                    src="https://www.youtube.com/embed/{{ $latestVideo['id'] }}" 
                    title="{{ $latestVideo['title'] }}" 
                    allowfullscreen>
                </iframe>
            </div>
        @else
            <p>沒有最新影片</p>
        @endif
    </div>
@endsection