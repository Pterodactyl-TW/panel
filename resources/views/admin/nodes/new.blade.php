@extends('layouts.admin')

@section('title')
    節點 &rarr; 新增
@endsection

@section('content-header')
    <h1>新增節點<small>建立可供伺服器安裝的新本機或遠端節點。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nodes') }}">節點</a></li>
        <li class="active">新增</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">基本詳細資訊</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">名稱</label>
                        <input type="text" name="name" id="pName" class="form-control" value="{{ old('name') }}"/>
                        <p class="text-muted small">字元限制：<code>a-zA-Z0-9_.-</code> 及 <code>[空白]</code>（最少 1 個，最多 100 個字元）。</p>
                    </div>
                    <div class="form-group">
                        <label for="pDescription" class="form-label">描述</label>
                        <textarea name="description" id="pDescription" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pLocationId" class="form-label">位置</label>
                        <select name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">節點可見性</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> 公開 </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> 私人 </label>
                            </div>
                        </div>
                        <p class="text-muted small">若將節點設為 <code>私人</code>，將無法對此節點自動部署。
                    </div>
                    <div class="form-group">
                        <label for="pFQDN" class="form-label">FQDN</label>
                        <input type="text" name="fqdn" id="pFQDN" class="form-control" value="{{ old('fqdn') }}"/>
                        <p class="text-muted small">請輸入用於連接 daemon 的網域名稱（例如 <code>node.example.com</code>）。僅在此節點未使用 SSL 時，<em>才可以</em>使用 IP 位址。</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">透過 SSL 通訊</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> 使用 SSL 連線</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> 使用 HTTP 連線</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">你的 Panel 目前設定為使用安全連線。為了讓瀏覽器能夠連線至你的節點，<strong>必須</strong>使用 SSL 連線。</p>
                        @else
                            <p class="text-muted small">大多數情況下，你應該選擇使用 SSL 連線。若使用 IP 位址或完全不想使用 SSL，請選擇 HTTP 連線。</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">位於代理伺服器後方</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> 未位於代理伺服器後方 </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> 位於代理伺服器後方 </label>
                            </div>
                        </div>
                        <p class="text-muted small">若你在如 Cloudflare 等代理伺服器後方執行 daemon，請選擇此項，讓 daemon 在啟動時略過憑證檢查。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">組態設定</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonBase" class="form-label">Daemon 伺服器檔案目錄</label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="form-control" value="/var/lib/pterodactyl/volumes" />
                            <p class="text-muted small">輸入伺服器檔案應儲存的目錄。<strong>若你使用 OVH，應檢查你的分割區配置方式，可能需要使用 <code>/home/daemon-data</code> 才會有足夠空間。</strong></p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemory" class="form-label">總記憶體</label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="form-control" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemoryOverallocate" class="form-label">記憶體超額配置</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="form-control" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">輸入可供新伺服器使用的總記憶體數量。若你想允許記憶體超額配置，請輸入想允許的百分比。若要停用超額配置檢查，請輸入 <code>-1</code>。輸入 <code>0</code> 則會在超出節點限制時禁止建立新伺服器。</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDisk" class="form-label">總磁碟空間</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="form-control" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDiskOverallocate" class="form-label">磁碟超額配置</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="form-control" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">輸入可供新伺服器使用的總磁碟空間數量。若你想允許磁碟空間超額配置，請輸入想允許的百分比。若要停用超額配置檢查，請輸入 <code>-1</code>。輸入 <code>0</code> 則會在超出節點限制時禁止建立新伺服器。</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonListen" class="form-label">Daemon 連接埠</label>
                            <input type="text" name="daemonListen" class="form-control" id="pDaemonListen" value="8080" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDaemonSFTP" class="form-label">Daemon SFTP 連接埠</label>
                            <input type="text" name="daemonSFTP" class="form-control" id="pDaemonSFTP" value="2022" />
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">daemon 會執行自己的 SFTP 管理容器，並不使用主要實體伺服器上的 SSHd 程序。<Strong>請勿使用與你實體伺服器 SSH 程序相同的連接埠。</strong>若你要在 CloudFlare&reg; 後方執行 daemon，應將 daemon 連接埠設為 <code>8443</code>，以允許透過 SSL 進行 websocket 代理。</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success pull-right">建立節點</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
