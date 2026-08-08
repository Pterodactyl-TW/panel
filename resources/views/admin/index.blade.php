@extends('layouts.admin')

@section('title')
    管理
@endsection

@section('content-header')
    <h1>管理總覽<small>快速瀏覽你的系統狀態。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li class="active">首頁</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box
            @if($version->isLatestPanel())
                box-success
            @else
                box-danger
            @endif
        ">
            <div class="box-header with-border">
                <h3 class="box-title">系統資訊</h3>
            </div>
            <div class="box-body">
                @if ($version->isLatestPanel())
                    你目前執行的 Pterodactyl Panel 版本為 <code>{{ config('app.version') }}</code>，你的 Panel 已是最新版本！
                @else
                    你的 Panel <strong>不是最新版本！</strong> 最新版本為 <a href="https://github.com/Pterodactyl-TW/panel/releases/v{{ $version->getPanel() }}" target="_blank"><code>{{ $version->getPanel() }}</code></a>，而你目前執行的版本為 <code>{{ config('app.version') }}</code>。你可以在<a href="https://pterodactyl.tw/panel/1.0/updating.html">這裡</a>找到更新 Panel 的相關說明。
                @endif
                <p class="text-muted" style="margin-top: 10px; margin-bottom: 0;">
                    <small>
                        繁體中文化版本最新版：<code>{{ $version->getPanel() }}</code>
                        Pterodactyl 官方最新版：<a href="https://github.com/pterodactyl/panel/releases/v{{ $version->getPanelOfficial() }}" target="_blank"><code>{{ $version->getPanelOfficial() }}</code></a>
                    </small>
                </p>
                <p class="text-muted" style="margin-top: 5px; margin-bottom: 0;">
                    <small>你現在使用的是由 <a href="https://pterodactyl.tw/" target="_blank">Pterodactyl 台灣翻譯團隊</a>維護，提供 Pterodactyl 官方的繁體中文化版本。</small>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDiscord() }}"><button class="btn btn-warning" style="width:100%;"><i class="fa fa-fw fa-support"></i> 取得協助 <small>（透過 Discord）</small></button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://pterodactyl.io"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-link"></i> 文件</button></a>
    </div>
    <div class="clearfix visible-xs-block">&nbsp;</div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://github.com/pterodactyl/panel"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-support"></i> GitHub</button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDonations() }}"><button class="btn btn-success" style="width:100%;"><i class="fa fa-fw fa-money"></i> 贊助此專案</button></a>
    </div>
</div>
@endsection
