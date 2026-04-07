{{-- 1. 宣告這個頁面要繼承我們剛剛做的 layouts.app 母版 --}}
@extends('layouts.app')

<div class="top">
    <h1>
        Welcome
    </h1>
</div>

{{-- 3. 把主要內容塞進母版名為 'content' 的洞裡 --}}
@section('content')
    <h2>
        簡介
    </h2>
    <p>
        這個網站就是放一些
    </p>
    <p>
        以前做過的事
    </p>
    <p>
        現在正在做的事
    </p>
    <p>
        還有未來想做的事
    </p>
@endsection