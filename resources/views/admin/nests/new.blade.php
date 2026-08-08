@extends('layouts.admin')

@section('title')
    新增 Nest
@endsection

@section('content-header')
    <h1>新增 Nest<small>設定要部署到所有節點的新 Nest。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nests') }}">Nests</a></li>
        <li class="active">新增</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.new') }}" method="POST">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">新增 Nest</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">名稱</label>
                        <div>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                            <p class="text-muted"><small>這應該是能概括此 Nest 底下所有 egg 的描述性分類名稱。</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">描述</label>
                        <div>
                            <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">儲存</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
