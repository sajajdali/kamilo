<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminController;
use App\Support\VoipIntegration;
use Illuminate\Http\Request;

class VoipConnectionController extends AdminController
{
    public function edit()
    {
        $settings = VoipIntegration::settings();
        $public = VoipIntegration::publicSettings();

        return view('appointment::admin.voip_connection.edit', compact('settings', 'public'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'voip_panel_enabled' => 'nullable',
            'voip_panel_url' => 'nullable|string|max:500',
            'voip_panel_username' => 'nullable|string|max:190',
            'voip_panel_password' => 'nullable|string|max:500',
        ]);

        VoipIntegration::saveConnectionFromRequest($request);

        return back()->with('successful', 'تنظیمات اتصال به سامانه ویپ ذخیره شد.');
    }
}
