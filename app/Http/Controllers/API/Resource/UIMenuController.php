<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\UIMenu;
use Illuminate\Http\Request;

class UIMenuController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, "Menu List",UIMenu::orderBy('menu_name')->get());
    }

    public function store(Request $request){
        $this->validate($request, [
            'menu_category' => 'required|string',
            'menu_name' => 'required|string',
            'menu_link' => 'required|string',
            'menu_permission' => 'required|string',
            'menu_icon' => 'string',
            'menu_parent_id' => 'integer',
        ]);

        try {
            $menu = UIMenu::create($request->all());
            return ResponseHelper::ajaxResponseBuilder(true,"Menu created successfully",$menu);
        } catch (\Exception $e) {
            return ResponseHelper::noDataErrorResponse($e->getMessage());
        }
    }

    public function update(Request $request, $menu_id){

    }

    public function destroy($menu_id){
        $menu = UIMenu::find($menu_id);
        $menu->delete();
        return ResponseHelper::noDataSuccessResponse(('Menu Deleted Successfully'));
    }
}
