@extends('layouts.admin')

@section('title')
    {{ $node->name }}：設定
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>配置你的節點設定。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nodes') }}">節點</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">設定</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">概覽</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.settings', $node->id) }}">設定</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">組態設定</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">連接埠配置</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">伺服器</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">設定</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">節點名稱</label>
                        <div>
                            <input type="text" autocomplete="off" name="name" class="form-control" value="{{ old('name', $node->name) }}" />
                            <p class="text-muted"><small>字元限制：<code>a-zA-Z0-9_.-</code> 及 <code>[空白]</code>（最少 1 個，最多 100 個字元）。</small></p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="description" class="control-label">描述</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="form-control">{{ $node->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">位置</label>
                        <div>
                            <select name="location_id" class="form-control">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (((int) old('location_id', $node->location_id)) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="public" class="control-label">允許自動配置 <sup><a data-toggle="tooltip" data-placement="top" title="是否允許自動配置到此節點？">?</a></sup></label>
                        <div>
                            <input type="radio" name="public" value="1" {{ (old('public', $node->public)) ? 'checked' : '' }} id="public_1" checked> <label for="public_1" style="padding-left:5px;">是</label><br />
                            <input type="radio" name="public" value="0" {{ (old('public', $node->public)) ? '' : 'checked' }} id="public_0"> <label for="public_0" style="padding-left:5px;">否</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fqdn" class="control-label">完整網域名稱 (FQDN)</label>
                        <div>
                            <input type="text" autocomplete="off" name="fqdn" class="form-control" value="{{ old('fqdn', $node->fqdn) }}" />
                        </div>
                        <p class="text-muted"><small>請輸入用於連接 daemon 的網域名稱（例如 <code>node.example.com</code>）。僅在此節點未使用 SSL 時，才可以使用 IP 位址。
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="為什麼我需要 FQDN？" data-content="為了保障你的伺服器與此節點之間的通訊安全，我們使用 SSL。我們無法為 IP 位址產生 SSL 憑證，因此你需要提供 FQDN。">為什麼？</a>
                            </small></p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> 透過 SSL 通訊</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" {{ (old('scheme', $node->scheme) === 'https') ? 'checked' : '' }}>
                                <label for="pSSLTrue"> 使用 SSL 連線</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" {{ (old('scheme', $node->scheme) !== 'https') ? 'checked' : '' }}>
                                <label for="pSSLFalse"> 使用 HTTP 連線</label>
                            </div>
                        </div>
                        <p class="text-muted small">大多數情況下，你應該選擇使用 SSL 連線。若使用 IP 位址或完全不想使用 SSL，請選擇 HTTP 連線。</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> 位於代理伺服器後方</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == false) ? 'checked' : '' }}>
                                <label for="pProxyFalse"> 未位於代理伺服器後方 </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == true) ? 'checked' : '' }}>
                                <label for="pProxyTrue"> 位於代理伺服器後方 </label>
                            </div>
                        </div>
                        <p class="text-muted small">若你在如 Cloudflare 等代理伺服器後方執行 daemon，請選擇此項，讓 daemon 在啟動時略過憑證檢查。</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-wrench"></i></span> 維護模式</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == false) ? 'checked' : '' }}>
                                <label for="pMaintenanceFalse"> 停用</label>
                            </div>
                            <div class="radio radio-warning radio-inline">
                                <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == true) ? 'checked' : '' }}>
                                <label for="pMaintenanceTrue"> 啟用</label>
                            </div>
                        </div>
                        <p class="text-muted small">若節點被標記為「維護中」，使用者將無法存取此節點上的伺服器。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">配置限制</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="memory" class="control-label">總記憶體</label>
                                <div class="input-group">
                                    <input type="text" name="memory" class="form-control" data-multiplicator="true" value="{{ old('memory', $node->memory) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="memory_overallocate" class="control-label">超額配置</label>
                                <div class="input-group">
                                    <input type="text" name="memory_overallocate" class="form-control" value="{{ old('memory_overallocate', $node->memory_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">輸入此節點可配置給伺服器的總記憶體數量。你也可以提供一個百分比，允許配置超過設定值的記憶體。</p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="disk" class="control-label">磁碟空間</label>
                                <div class="input-group">
                                    <input type="text" name="disk" class="form-control" data-multiplicator="true" value="{{ old('disk', $node->disk) }}"/>
                                    <span class="input-group-addon">MiB</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="disk_overallocate" class="control-label">超額配置</label>
                                <div class="input-group">
                                    <input type="text" name="disk_overallocate" class="form-control" value="{{ old('disk_overallocate', $node->disk_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">輸入此節點可供伺服器配置的總磁碟空間數量。你也可以提供一個百分比，決定允許超出設定限制的磁碟空間量。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">一般組態設定</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="disk_overallocate" class="control-label">最大網頁上傳檔案大小</label>
                        <div class="input-group">
                            <input type="text" name="upload_size" class="form-control" value="{{ old('upload_size', $node->upload_size) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted"><small>輸入可透過網頁版檔案管理員上傳的檔案最大大小。</small></p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="daemonListen" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Daemon 連接埠</label>
                                <div>
                                    <input type="text" name="daemonListen" class="form-control" value="{{ old('daemonListen', $node->daemonListen) }}"/>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daemonSFTP" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Daemon SFTP 連接埠</label>
                                <div>
                                    <input type="text" name="daemonSFTP" class="form-control" value="{{ old('daemonSFTP', $node->daemonSFTP) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted"><small>daemon 會執行自己的 SFTP 管理容器，並不使用主要實體伺服器上的 SSHd 程序。<Strong>請勿使用與你實體伺服器 SSH 程序相同的連接埠。</strong></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">儲存設定</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-6">
                        <div>
                            <input type="checkbox" name="reset_secret" id="reset_secret" /> <label for="reset_secret" class="control-label">重設 Daemon 主金鑰</label>
                        </div>
                        <p class="text-muted"><small>重設 daemon 主金鑰將使來自舊金鑰的所有請求失效。此金鑰用於 daemon 上所有敏感操作，包括建立與刪除伺服器。基於安全考量，建議定期更換此金鑰。</small></p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! method_field('PATCH') !!}
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">儲存變更</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('[data-toggle="popover"]').popover({
        placement: 'auto'
    });
    $('select[name="location_id"]').select2();
    </script>
@endsection
