<?php

namespace Ophim\Core\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Prologue\Alerts\Facades\Alert;
use Backpack\Settings\app\Models\Setting;
use Appstract\Opcache\OpcacheFacade as OPcache;

class QuickActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_cache()
    {
        Artisan::call('optimize:clear');
        Alert::success("Xóa cache thành công")->flash();
        return back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ping()
    {
        $status = ping_sitemap( url('/sitemap.xml'))."& ".ping_pingomatic( url(""), Setting::get('site_homepage_title'));
        Alert::success($status)->flash();
        return back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_opcache()
    {
        OPcache::clear();
        Alert::success("Xóa opcache thành công")->flash();
        return back();
    }
    public function turn_ads()
    {
        if(setting('hide_ads_boss')=='1'){
            $msg = "Tắt";
            $value = '1';
        }else{
            $msg = "Mở";
            $value = '0';
        }
        Setting::where('id', 'hide_ads_boss')->update([
            'value' => $value
        ]);
        Alert::success($msg." quảng cáo thành công")->flash();
        return back();
    }
}
