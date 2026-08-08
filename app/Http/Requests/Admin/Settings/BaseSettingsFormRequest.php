<?php

namespace Pterodactyl\Http\Requests\Admin\Settings;

use Illuminate\Validation\Rule;
use Pterodactyl\Traits\Helpers\AvailableLanguages;
use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class BaseSettingsFormRequest extends AdminFormRequest
{
    use AvailableLanguages;

    public function rules(): array
    {
        return [
            'app:name' => 'required|string|max:191',
            'pterodactyl:auth:2fa_required' => 'required|integer|in:0,1,2',
            'app:locale' => ['required', 'string', Rule::in(array_keys($this->getAvailableLanguages()))],
            'pterodactyl:name_display_spacing' => ['required', 'string', Rule::in(['auto', 'always', 'never'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'app:name' => '公司名稱',
            'pterodactyl:auth:2fa_required' => '要求啟用兩步驟驗證',
            'app:locale' => '預設語言',
            'pterodactyl:name_display_spacing' => '使用者姓名顯示間距',
        ];
    }
}
