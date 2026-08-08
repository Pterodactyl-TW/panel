<?php

namespace Pterodactyl\Http\Requests\Admin\Settings;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class AdvancedSettingsFormRequest extends AdminFormRequest
{
    /**
     * Return all the rules to apply to this request's data.
     */
    public function rules(): array
    {
        return [
            'recaptcha:enabled' => 'required|in:true,false',
            'recaptcha:secret_key' => 'required|string|max:191',
            'recaptcha:website_key' => 'required|string|max:191',
            'pterodactyl:guzzle:timeout' => 'required|integer|between:1,60',
            'pterodactyl:guzzle:connect_timeout' => 'required|integer|between:1,60',
            'pterodactyl:client_features:allocations:enabled' => 'required|in:true,false',
            'pterodactyl:client_features:allocations:range_start' => [
                'nullable',
                'required_if:pterodactyl:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
            ],
            'pterodactyl:client_features:allocations:range_end' => [
                'nullable',
                'required_if:pterodactyl:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
                'gt:pterodactyl:client_features:allocations:range_start',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'recaptcha:enabled' => '啟用 reCAPTCHA',
            'recaptcha:secret_key' => 'reCAPTCHA 密鑰',
            'recaptcha:website_key' => 'reCAPTCHA 網站金鑰',
            'pterodactyl:guzzle:timeout' => 'HTTP 請求逾時時間',
            'pterodactyl:guzzle:connect_timeout' => 'HTTP 連線逾時時間',
            'pterodactyl:client_features:allocations:enabled' => '啟用自動建立連接埠配置',
            'pterodactyl:client_features:allocations:range_start' => '起始連接埠',
            'pterodactyl:client_features:allocations:range_end' => '結束連接埠',
        ];
    }
}
