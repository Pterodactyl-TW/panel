@extends('layouts.admin')

@section('title')
    資料庫主機 &rarr; 檢視 &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <h1>{{ $host->name }}<small>檢視此資料庫主機相關的資料庫與詳細資訊。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.databases') }}">資料庫主機</a></li>
        <li class="active">{{ $host->name }}</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">主機詳細資訊</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">名稱</label>
                        <input type="text" id="pName" name="name" class="form-control" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div class="form-group">
                        <label for="pHost" class="form-label">主機</label>
                        <input type="text" id="pHost" name="host" class="form-control" value="{{ old('host', $host->host) }}" />
                        <p class="text-muted small"><em>從 Panel</em> 連線到此 MySQL 主機以新增資料庫時所使用的 IP 位址或 FQDN。</p>
                    </div>
                    <div class="form-group">
                        <label for="pPort" class="form-label">連接埠</label>
                        <input type="text" id="pPort" name="port" class="form-control" value="{{ old('port', $host->port) }}" />
                        <p class="text-muted small">此主機執行 MySQL 所使用的連接埠。</p>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">連結節點</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="">無</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">此設定唯一的作用，是在對所選節點上的伺服器新增資料庫時，預設使用這個資料庫主機。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">使用者詳細資訊</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pUsername" class="form-label">使用者名稱</label>
                        <input type="text" name="username" id="pUsername" class="form-control" value="{{ old('username', $host->username) }}" />
                        <p class="text-muted small">具有足夠權限在系統上建立新使用者與資料庫的帳號使用者名稱。</p>
                    </div>
                    <div class="form-group">
                        <label for="pPassword" class="form-label">密碼</label>
                        <input type="password" name="password" id="pPassword" class="form-control" />
                        <p class="text-muted small">上方所定義帳號的密碼。留空則繼續使用目前指派的密碼。</p>
                    </div>
                    <hr />
                    <p class="text-danger small text-left">此資料庫主機所定義的帳號<strong>必須</strong>擁有 <code>WITH GRANT OPTION</code> 權限。若所定義的帳號沒有此權限，建立資料庫的請求<em>將會</em>失敗。<strong>請勿使用與此 Panel 相同的帳號資訊來設定 MySQL。</strong></p>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">儲存</button>
                    <button name="_method" value="DELETE" class="btn btn-sm btn-danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">資料庫</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>伺服器</th>
                        <th>資料庫名稱</th>
                        <th>使用者名稱</th>
                        <th>連線來源</th>
                        <th>最大連線數</th>
                        <th></th>
                    </tr>
                    @foreach($databases as $database)
                        <tr>
                            <td class="middle"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="middle">{{ $database->database }}</td>
                            <td class="middle">{{ $database->username }}</td>
                            <td class="middle">{{ $database->remote }}</td>
                            @if($database->max_connections != null)
                                <td class="middle">{{ $database->max_connections }}</td>
                            @else
                                <td class="middle">無限制</td>
                            @endif
                            <td class="text-center">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}">
                                    <button class="btn btn-xs btn-primary">管理</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($databases->hasPages())
                <div class="box-footer with-border">
                    <div class="col-md-12 text-center">{!! $databases->render() !!}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2();
    </script>
@endsection
