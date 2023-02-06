<?php

namespace Ophim\Core\Controllers\Admin;

use Ophim\Core\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Ophim\Core\Models\Category;

/**
 * Class CategoryCrudController
 * @package Ophim\Core\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\Ophim\Core\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->authorize('browse', Category::class);

        CRUD::column('name')->label('Tên')->type('text');
        CRUD::column('slug')->label('Đường dẫn tĩnh')->type('text');
        CRUD::column('seo_title')->label('Tiêu đề')->type('text');
        CRUD::column('seo_des')->label('Mô tả')->type('text');
        CRUD::column('seo_key')->label('Từ khoá')->type('text');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->authorize('create', Category::class);

        CRUD::setValidation(CategoryRequest::class);

        CRUD::field('name')->label('Tên')->type('text');
        CRUD::field('slug')->label('Đường dẫn tĩnh')->type('text');
        CRUD::field('seo_title')->label('SEO Tiêu đề')->type('text');
        CRUD::field('seo_des')->label('SEO Mô tả')->type('textarea');
        CRUD::field('seo_key')->label('SEO Từ khoá')->type('text');
        CRUD::addField([
            'name' => 'thumb_url', 
            'label' => 'Ảnh Thumb', 
            'type' => 'ckfinder', 
            'has_preview' => true,
            'preview' => [
                'width' => 'auto', 
                'height' => '340px'
            ]
        ]);
        CRUD::addField([
            'name' => 'poster_url', 
            'label' => 'Ảnh Poster', 
            'type' => 'ckfinder', 
            'has_preview' => true,
            'preview' => [
                'width' => 'auto', 
                'height' => '340px'
            ]
        ]);
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


        $this->setupCreateOperation();
    }

    protected function setupDeleteOperation()
    {
        $this->authorize('delete', $this->crud->getEntryWithLocale($this->crud->getCurrentEntryId()));
    }
}
