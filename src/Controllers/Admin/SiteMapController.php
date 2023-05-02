<?php

namespace Ophim\Core\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL as LARURL;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Ophim\Core\Models\Actor;
use Ophim\Core\Models\Catalog;
use Ophim\Core\Models\Category;
use Ophim\Core\Models\Director;
use Ophim\Core\Models\Movie;
use Ophim\Core\Models\SiteMaps;
use Ophim\Core\Models\Region;
use Ophim\Core\Models\Studio;
use Ophim\Core\Models\Tag;
use Prologue\Alerts\Facades\Alert;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SiteMapController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sitemap');
        CRUD::setEntityNameStrings('site map', 'site map');
    }

    protected function setupListOperation()
    {
        $this->setupCreateOperation();
    }
    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::addField(['name' => 'sitemap', 'type' => 'custom_html', 'value' => 'Sitemap sẽ được lưu tại đường dẫn: <i>' . url('/sitemap.xml') . '</i>']);
        $this->crud->addSaveAction([
            'name' => 'save_and_new',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route;
            },
            'button_text' => 'Tạo sitemap',
        ]);

        $this->crud->setOperationSetting('showSaveActionChange', false);
    }
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
   

    public function store(Request $request)
    {
        SiteMaps::updateSitemap(true, true);
    }
}