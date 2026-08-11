@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg：{{ $egg->name }}
@endsection

@section('content-header')
    <h1>{{ $egg->name }}<small>{{ str_limit($egg->description, 50) }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nests') }}">Nests</a></li>
        <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
        <li class="active">{{ $egg->name }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{ route('admin.nests.egg.view', $egg->id) }}">組態設定</a></li>
                <li><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">變數</a></li>
                <li><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">安裝腳本</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group no-margin-bottom">
                                <label for="pName" class="control-label">Egg 檔案</label>
                                <div>
                                    <input type="file" name="import_file" class="form-control" style="border: 0;margin-left:-10px;" />
                                    <p class="text-muted small no-margin-bottom">若你想上傳新的 JSON 檔案來取代此 Egg 的設定，只要在此選擇檔案並按下「更新 Egg」即可。這不會變更現有伺服器的啟動指令字串或 Docker 映像檔。</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PUT" class="btn btn-sm btn-danger pull-right">更新 Egg</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
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
                                <label for="pName" class="control-label">名稱 <span class="field-required"></span></label>
                                <input type="text" id="pName" name="name" value="{{ $egg->name }}" class="form-control" />
                                <p class="text-muted small">用來識別此 Egg 的簡單易懂名稱。</p>
                            </div>
                            <div class="form-group">
                                <label for="pUuid" class="control-label">UUID</label>
                                <input type="text" id="pUuid" readonly value="{{ $egg->uuid }}" class="form-control" />
                                <p class="text-muted small">這是此 Egg 的全域唯一識別碼，Wings 會用它作為識別依據。</p>
                            </div>
                            <div class="form-group">
                                <label for="pAuthor" class="control-label">作者</label>
                                <input type="text" id="pAuthor" readonly value="{{ $egg->author }}" class="form-control" />
                                <p class="text-muted small">此版本 Egg 的作者。若上傳由不同作者提供的新 Egg 組態設定，此欄位將會變更。</p>
                            </div>
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Docker 映像檔 <span class="field-required"></span></label>
                                <textarea id="pDockerImages" name="docker_images" class="form-control" rows="4">{{ implode(PHP_EOL, $images) }}</textarea>
                                <p class="text-muted small">
                                    使用此 egg 的伺服器可用的 Docker 映像檔，每行輸入一個。若提供多個值，
                                    使用者將能從這份清單中選擇。你也可以選擇在映像檔前加上顯示名稱，
                                    後面接上一個直線符號，再接上映像檔網址，藉此提供自訂顯示名稱。
                                    範例：<code>顯示名稱|ghcr.io/my/egg</code>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" @if($egg->force_outgoing_ip) checked @endif />
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
                                <label for="pDescription" class="control-label">描述</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ $egg->description }}</textarea>
                                <p class="text-muted small">此 Egg 的描述，將視需要顯示在 Panel 各處。</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">啟動指令 <span class="field-required"></span></label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="8">{{ $egg->startup }}</textarea>
                                <p class="text-muted small">使用此 Egg 的新伺服器所使用的預設啟動指令。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigFeatures" class="control-label">功能</label>
                                <div>
                                    <select class="form-control" name="features[]" id="pConfigFeatures" multiple>
                                        @foreach(($egg->features ?? []) as $feature)
                                            <option value="{{ $feature }}" selected>{{ $feature }}</option>
                                        @endforeach
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
                                <p>除非你了解此系統的運作方式，否則不應該編輯以下組態設定選項。若錯誤修改，可能會導致 Wings 無法運作。</p>
                                <p>除非你從「從其他項目複製設定」下拉選單選擇了另一個選項，否則所有欄位皆為必填；若選擇了其他選項，欄位可留空以使用該 Egg 的值。</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">從其他項目複製設定</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">無</option>
                                    @foreach($egg->nest->eggs as $o)
                                        <option value="{{ $o->id }}" {{ ($egg->config_from !== $o->id) ?: 'selected' }}>{{ $o->name }} &lt;{{ $o->author }}&gt;</option>
                                    @endforeach
                                </select>
                                <p class="text-muted small">若你想預設使用其他 Egg 的設定，請從上方選單中選擇。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">停止指令</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ $egg->config_stop }}" />
                                <p class="text-muted small">傳送給伺服器程序以正常停止它的指令。若你需要傳送 <code>SIGINT</code>，請在此輸入 <code>^C</code>。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">日誌設定</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ ! is_null($egg->config_logs) ? json_encode(json_decode($egg->config_logs), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明日誌檔案儲存在哪裡，以及 Wings 是否應該建立自訂日誌。</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">組態設定檔</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ ! is_null($egg->config_files) ? json_encode(json_decode($egg->config_files), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明要修改哪些組態設定檔以及要變更哪些部分。</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">啟動設定</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ ! is_null($egg->config_startup) ? json_encode(json_decode($egg->config_startup), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">這應該是一份 JSON 格式的內容，說明 Wings 在啟動伺服器時應該尋找哪些值來判斷是否已完成啟動。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="PATCH" class="btn btn-primary btn-sm pull-right">儲存</button>
                    <a href="{{ route('admin.nests.egg.export', $egg->id) }}" class="btn btn-sm btn-info pull-right" style="margin-right:10px;">匯出</a>
                    <button id="deleteButton" type="submit" name="_method" value="DELETE" class="btn btn-danger btn-sm muted muted-hover">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pConfigFrom').select2();
    $('#deleteButton').on('mouseenter', function (event) {
        $(this).find('i').html(' 刪除 Egg');
    }).on('mouseleave', function (event) {
        $(this).find('i').html('');
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
