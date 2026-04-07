@extends('layouts.app')

@section('title', 'Biggest\'s website')
    <div class="top">
        <h1>
            聯絡方式
        </h1>
    </div>

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/contacts.blade.css') }}?v={{ time() }}">
@endsection

@section('content')
    <div>
        FB, DC要加之前說一下，不然我會覺得是詐騙。
    </div>
    <div class="contact-container">
        <a href="https://facebook.com/wu.pin.fan.179428" target="_blank" rel="noopener noreferrer" class="contact-card fb">
            <div class="contact-icon">FB</div>
            <div class="contact-info">
                <h4>Facebook</h4>
                <p>前往個人主頁</p>
            </div>
        </a>

        <a href="https://x.com/BggestShark" target="_blank" rel="noopener noreferrer" class="contact-card x">
            <div class="contact-icon">X</div>
            <div class="contact-info">
                <h4>X (Twitter)</h4>
                <p>@Biggest Shark</p>
            </div>
        </a>

        <div class="contact-card discord">
            <div class="contact-icon">DC</div>
            <div class="contact-info">
                <h4>Discord</h4>
                <p>biggestshark</p>
            </div>
        </div>

        <a href="mailto:wu920327@gmail.com" class="contact-card gmail">
            <div class="contact-icon">Mail</div>
            <div class="contact-info">
                <h4>Gmail</h4>
                <p>wu920327@gmail.com</p>
            </div>
        </a>
    </div>
@endsection