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
    <div class="page-sections">
        <section class="page-section">
            <h2>
                Biggest Shark
            </h2>
            <p>
                可以叫我shark，或是鯊魚
            </p>
        </section>

        <section class="page-section">
            <h2>
                興趣
            </h2>
            <p>
                平常就玩玩pc遊戲和看動畫
            </p>
            <p>
                遊戲可以看看遊戲成就那邊
            </p>
            <p>
                動畫目前只看GL，參考下面的Anilist
            </p>
            <p>
                還有一個書籍清單google sheet
            </p>
            <a
                href="https://anilist.co/user/BiggestShark/animelist"
                class="sheet-card"
                target="_blank"
                rel="noopener noreferrer">
                <div class="sheet-icon">
                    📺
                </div>
                <div class="sheet-info">
                    <span class="sheet-title">動畫清單</span>
                </div>
            </a>
            <a
                href="https://docs.google.com/spreadsheets/d/1M0vkh6yoU0GnUAtRIjRUhoDCVlRwu_ctgd4bbNQo26Q/edit?gid=0#gid=0"
                class="sheet-card"
                target="_blank"
                rel="noopener noreferrer">
                <div class="sheet-icon">
                    📚
                </div>
                <div class="sheet-info">
                    <span class="sheet-title">Biggest Shark的圖書館</span>
                </div>
            </a>
        </section>

        <section class="page-section">
            <h2>
                電腦硬體
            </h2>
            <dl class="hardware-list">
                <div class="hardware-item">
                    <dt>
                        CPU
                    </dt>
                    <dd>
                        Intel Core i5-12600KF
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        GPU
                    </dt>
                    <dd>
                        NVIDIA GeForce RTX 4060
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        記憶體
                    </dt>
                    <dd>
                        Kingston 32 GB（16 GB × 2）
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        主機板
                    </dt>
                    <dd>
                        ASUS TUF GAMING B760M-PLUS WIFI II
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        滑鼠
                    </dt>
                    <dd>
                        Razer DeathAdder V4 Pro
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        鍵盤
                    </dt>
                    <dd>
                        ROG Strix Scope NX TKL Moonlight White
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        繪圖板
                    </dt>
                    <dd>
                        Wacom CTL-4100
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        螢幕
                    </dt>
                    <dd>
                        Acer VG250Q F 300hz
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        麥克風
                    </dt>
                    <dd>
                        AVerMedia AM310 USB Microphone
                    </dd>
                </div>
                <div class="hardware-item">
                    <dt>
                        攝影機
                    </dt>
                    <dd>
                        Streamplify CAM
                    </dd>
                </div>
            </dl>
        </section>
    </div>
@endsection
