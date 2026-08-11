@extends('layouts.admin')

@section('title')
    Nests &rarr; 新增 Egg
@endsection

@section('content-header')
    <h1>新增 Egg<small>建立一個新的 Egg 以指派給伺服器。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nests') }}">Nests</a></li>
        <li class="active">新增 Egg</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">組態設定</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pNestId" class="form-label">關聯 Nest</label>
                                <div>
                                    <select name="nest_id" id="pNestId">
                                        @foreach($nests as $nest)
                                            <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted small">可以把 Nest 想成是一種分類。你可以在一個 Nest 中放入多個 Egg，但建議每個 Nest 只放入彼此相關的 Egg。</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pName" class="form-label">名稱</label>
                                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="form-control" />
                                <p class="text-muted small">用來識別此 Egg 的簡單易懂名稱，使用者會看到這個名稱作為其遊戲伺服器的類型。</p>
                            </div>
                            <div class="form-group">
                                <label for="pDescription" class="form-label">描述</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ old('description') }}</textarea>
                                <p class="text-muted small">此 Egg 的描述。</p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" {{ \Pterodactyl\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
                                    <label for="pForceOutgoingIp" class="strong">強制對外 IP</label>
                                    <p class="text-muted small">
                                        強制所有對外的網路流量將來源 IP 進行 NAT 轉換為伺服器主要配置 IP。
                                        當節點擁有多個公開 IP 位址時，某些遊戲需要此設定才能正常運作。
                                        <br>
                                        <strong>
                                            啟用此選項會停用使用此 egg 的伺服器的內部網路功能，
                                            導致它們無法在內部存取同一節點上的其他伺服器。
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Docker 映像檔</label>
                                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="ghcr.io/pterodactyl/yolks" class="form-control">{{ old('docker_images') }}</textarea>
                                <p class="text-muted small">使用此 egg 的伺服器可用的 Docker 映像檔，每行輸入一個。若提供多個值，使用者將能從這份清單中選擇。</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">啟動指令</label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="10">{{ old('startup') }}</textarea>
                                <p class="text-muted small">使用此 Egg 建立新伺服器時所使用的預設啟動指令。你可以依需求為每台伺服器個別變更。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigFeatures" class="control-label">功能</label>
                                <div>
                                    <select class="form-control" name="features[]" id="pConfigFeatures" multiple>
                                    </select>
                                    <p class="text-muted small">此 egg 附加的額外功能，適用於設定額外的 Panel 修改功能。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">程序管理</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <p>除非你從「從其他項目複製設定」下拉選單選擇了另一個選項，否則所有欄位皆為必填；若選擇了其他選項，欄位可留空以使用該選項的值。</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">從其他項目複製設定</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">無</option>
                                </select>
                                <p class="text-muted small">若你想預設使用其他 Egg 的設定，請從上方下拉選單中選擇。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">停止指令</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ old('config_stop') }}" />
                                <p class="text-muted small">傳送給伺服器程序以正常停止它的指令。若你需要傳送 <code>SIGINT</code>，請在此輸入 <code>^C</code>。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">日誌設定</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ old('config_logs') }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明日誌檔案儲存在哪裡，以及 Wings 是否應該建立自訂日誌。</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">組態設定檔</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ old('config_files') }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明要修改哪些組態設定檔以及要變更哪些部分。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">啟動設定</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ old('config_startup') }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明 Wings 在啟動伺服器時應該尋找哪些值來判斷是否已完成啟動。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success btn-sm pull-right">建立</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('#pNestId').select2().change();
        $('#pConfigFrom').select2();
    });
    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">無</option>').select2({
            data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
    });
    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    $('#pConfigFeatures').select2({
        tags: true,
        selectOnClose: false,
        tokenSeparators: [',', ' '],
    });
    </script>
@endsection
