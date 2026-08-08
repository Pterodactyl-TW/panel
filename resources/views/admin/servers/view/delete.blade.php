@extends('layouts.admin')

@section('title')
    伺服器 — {{ $server->name }}：刪除
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>從 Panel 中刪除此伺服器。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.servers') }}">伺服器</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">刪除</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">安全刪除伺服器</h3>
            </div>
            <div class="box-body">
                <p>此操作將嘗試從 Panel 與 daemon 中一併刪除此伺服器。若其中一方回報錯誤，此操作將被取消。</p>
                <p class="text-danger small">刪除伺服器是不可復原的操作。<strong>所有伺服器資料</strong>（包括檔案與使用者）都將從系統中移除。</p>
            </div>
            <div class="box-footer">
                <form id="deleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <button id="deletebtn" class="btn btn-danger">安全刪除此伺服器</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">強制刪除伺服器</h3>
            </div>
            <div class="box-body">
                <p>此操作將嘗試從 Panel 與 daemon 中一併刪除此伺服器。若 daemon 沒有回應或回報錯誤，刪除操作仍會繼續進行。</p>
                <p class="text-danger small">刪除伺服器是不可復原的操作。<strong>所有伺服器資料</strong>（包括檔案與使用者）都將從系統中移除。若 daemon 回報錯誤，此方式可能會在你的 daemon 上留下殘留檔案。</p>
            </div>
            <div class="box-footer">
                <form id="forcedeleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="force_delete" value="1" />
                    <button id="forcedeletebtn"" class="btn btn-danger">強制刪除此伺服器</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#deletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: '',
            type: 'warning',
            text: '你確定要刪除此伺服器嗎？此操作無法復原，所有資料將立即被移除。',
            showCancelButton: true,
            confirmButtonText: '刪除',
            confirmButtonColor: '#d9534f',
            closeOnConfirm: false
        }, function () {
            $('#deleteform').submit()
        });
    });

    $('#forcedeletebtn').click(function (event) {
        event.preventDefault();
        swal({
            title: '',
            type: 'warning',
            text: '你確定要刪除此伺服器嗎？此操作無法復原，所有資料將立即被移除。',
            showCancelButton: true,
            confirmButtonText: '刪除',
            confirmButtonColor: '#d9534f',
            closeOnConfirm: false
        }, function () {
            $('#forcedeleteform').submit()
        });
    });
    </script>
@endsection
