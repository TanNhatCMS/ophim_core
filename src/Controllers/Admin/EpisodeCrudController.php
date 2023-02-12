<?php

namespace Ophim\Core\Controllers\Admin;

use Ophim\Core\Requests\MovieRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ophim\Core\Models\Actor;
use Ophim\Core\Models\Director;
use Ophim\Core\Models\Movie;
use Ophim\Core\Models\Region;
use Ophim\Core\Models\Studio;
use Ophim\Core\Models\Category;
use Ophim\Core\Models\Tag;

/**
 * Class MovieCrudController
 * @package Ophim\Core\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EpisodeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as backpackUpdate;
    }
   

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\Ophim\Core\Models\Movie::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/episode');
        CRUD::setEntityNameStrings('episodes', 'episode');
        CRUD::setUpdateView('ophim::movies.edit',);
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->authorize('browse', Movie::class);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number','tab'=>'Thông tin phim']);
         */
        $this->crud->enableExportButtons();
        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'select2',
            'label' => 'Tình trạng'
        ], function () {
            return [
                'trailer' => 'Sắp chiếu',
                'ongoing' => 'Đang chiếu',
                'completed' => 'Hoàn thành'
            ];
        }, function ($val) {
            $this->crud->addClause('where', 'status', $val);
        });

        $this->crud->addFilter([
            'name'  => 'type',
            'type'  => 'select2',
            'label' => 'Định dạng'
        ], function () {
            return [
                'single' => 'Phim lẻ',
                'series' => 'Phim bộ'
            ];
        }, function ($val) {
            $this->crud->addClause('where', 'type', $val);
        });

        $this->crud->addFilter([
            'name'  => 'category_id',
            'type'  => 'select2',
            'label' => 'Thể loại'
        ], function () {
            return Category::all()->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->query = $this->crud->query->whereHas('categories', function ($query) use ($value) {
                $query->where('id', $value);
            });
        });

        $this->crud->addFilter([
            'name'  => 'other',
            'type'  => 'select2',
            'label' => 'Thông tin'
        ], function () {
            return [
                'thumb_url-' => 'Thiếu ảnh thumb',
                'poster_url-' => 'Thiếu ảnh poster',
                'trailer_url-' => 'Thiếu trailer',
                'language-vietsub' => 'Vietsub',
                'language-thuyết minh' => 'Thuyết minh',
                'language-lồng tiếng' => 'Lồng tiếng',
            ];
        }, function ($values) {
            $value = explode("-", $values);
            $field = $value[0];
            $val = $value[1];
            if($field === 'language') {
                $this->crud->query->where($field, 'like', '%' . $val . '%');
            } else {
                $this->crud->query->where($field, '')->orWhere($field, NULL);
            }
        });
        $this->crud->addFilter([
            'name'  => 'showntimes_in_weekday',
            'type'  => 'select2',
            'label' => 'Ngày chiếu phim'
        ], function () {
            return [
                '0' => 'Hằng ngày',
                '1' => 'Chủ Nhật',
                '2' => 'Thứ 2',
                '3' => 'Thứ 3',
                '4' => 'Thứ 4',
                '5' => 'Thứ 5',
                '6' => 'Thứ 6',
                '7' => 'Thứ 7'
            ];
        }, function ($val) {
            $this->crud->addClause('where', 'showntimes_in_weekday', $val);
        });
        $this->crud->addFilter(
            [
                'type'  => 'simple',
                'name'  => 'is_shown_in_weekly',
                'label' => 'Lịch chiếu phim'
            ],
            false,
            function () {
                $this->crud->addClause('where', 'is_shown_in_weekly', true);
            }
        );
        $this->crud->addFilter(
            [
                'type'  => 'simple',
                'name'  => 'is_recommended',
                'label' => 'Đề cử'
            ],
            false,
            function () {
                $this->crud->addClause('where', 'is_recommended', true);
            }
        );

        $this->crud->addFilter(
            [
                'type'  => 'simple',
                'name'  => 'is_shown_in_theater',
                'label' => 'Chiếu rạp'
            ],
            false,
            function () {
                $this->crud->addClause('where', 'is_shown_in_theater', true);
            }
        );
      
        CRUD::addButtonFromModelFunction('line', 'open_view', 'openView', 'beginning');
        CRUD::addColumn([
            'name' => 'name',
            'origin_name' => 'origin_name',
            'publish_year' => 'publish_year',
            'status' => 'status',
            'movie_type' => 'type',
            'episode_current' => 'episode_current',
            'thumb_url' => 'thumb_url',
            'label' => 'Thông tin',
            'type' => 'view',
            
            'view' => 'ophim::movies.columns.column_movie_info',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')->orWhere('origin_name', 'like', '%' . $searchTerm . '%');
            }
        ]);
        CRUD::addColumn(['name' => 'view_total', 'label' => 'Lượt xem', 'type' => 'number',]);
        CRUD::addColumn(['name' => 'user_name', 'label' => 'Cập nhật bởi', 'type' => 'text',]);
        CRUD::addColumn([
            'name' => 'categories', 
            'label' => 'Thể loại', 
            'view' => 'ophim::movies.columns.column_relationship',
        ]);
        CRUD::addColumn(['name' => 'updated_at', 'label' => 'Cập nhật lúc', 'type' => 'datetime', 'format' => 'DD/MM/YYYY HH:mm:ss']);
    }



    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->authorize('update', $this->crud->getEntryWithLocale($this->crud->getCurrentEntryId()));

       
        CRUD::addField([
            'name' => 'episodes',
            'type' => 'view',
            'view' => 'ophim::movies.inc.episode',
            'tab' => 'Danh sách tập phim'
        ],);

        CRUD::addField([
            'name' => 'timestamps', 
            'label' => 'Cập nhật thời gian', 
            'type' => 'switch', 
            'color'    => 'primary', // May be any bootstrap color class or an hex color
            'onLabel' => '✓',
            'offLabel' => '✕', 
            'value' => false,
            'tab' => 'Danh sách tập phim'
        ]);
    }

    public function update(Request $request)
    {
        return $this->backpackUpdate();
    }

}
