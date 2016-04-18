<?php

namespace Palamike\Foundation\Http\Controllers\Test;

use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class VueElementTestController extends Controller
{
    protected $permissions = [
        'index' => 'test_vue_view',
        'show' => 'test_vue_view',
        'create' => 'test_vue_add',
        'store' => 'test_vue_add',
        'edit' => 'test_vue_edit',
        'update' => 'test_vue_edit',
        'destroy' => 'test_vue_delete'
    ];

    /**
     * VueElementTestController constructor.
     */
    public function __construct()
    {
        $this->fireRouteNavigationEvent();
        $this->checkPermission();
    }

    /**
     * Return the application information for start up.
     *
     * @return \Illuminate\Http\Response
     */
    public function application(){
        return [
            'lang' => app_locale(),
            'locales' => [
                app_locale() => [
                    'common' => get_lang_json('common.php','vendor/foundation'),
                    'test' => get_lang_json('test.php','vendor/foundation')
                ]
            ],
            'permissions' => $this->mapPermissions(),
            'test_forms' => [
                ['type' => 'text', 'name' => 'server_text_el1', 'label' => 'Server Text Element-1', 'value' => 'Server Text-Value-1', 'placeholder' => 'Input Server Text'],
                ['type' => 'text', 'name' => 'server_text_el2', 'label' => 'Server Text Element-2', 'value' => 'Server Text-Value-2'],
                ['type' => 'email', 'name' => 'server_email_el1', 'label' => 'Server Email Element-1', 'value' => 'server1@domains.tld', 'placeholder' => 'user@domain.tld'],
                ['type' => 'email', 'name' => 'server_email_el2', 'label' => 'Server Email Element-2', 'value' => '' ],
                ['type' => 'checkbox', 'name' => 'server_checkbox_el1', 'label' => 'Server Checkbox Element-1', 'value' => 'isServerChecked', 'trueValue' => 'isServerChecked','falseValue' => 'isServerUnchecked' ],
                ['type' => 'checkbox', 'name' => 'server_checkbox_el2', 'label' => 'Server Email Element-2', 'value' => 'isServerUnchecked', 'trueValue' => 'isServerChecked','falseValue' => 'isServerUnchecked' ],
            ]
        ];
    }
    
    public function query(){
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('foundation::test.vue.element',['permissions' => $this->mapPermissions()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
