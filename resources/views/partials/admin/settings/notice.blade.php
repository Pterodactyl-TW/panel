@section('settings::notice')
    @if(config('pterodactyl.load_environment_only', false))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger">
                    你的 Panel 目前設定為僅從環境變數讀取設定。若要動態載入設定，你需要在環境設定檔中設定 <code>APP_ENVIRONMENT_ONLY=false</code>。
                </div>
            </div>
        </div>
    @endif
@endsection
