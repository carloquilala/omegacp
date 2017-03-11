<?php

namespace artworx\omegacp\Http\Controllers;

use Illuminate\Http\Request;
use artworx\omegacp\Facades\Omega;

class OmegaMenuController extends Controller
{
    public function builder($id)
    {
        Omega::canOrFail('edit_menus');

        $menu = Omega::model('Menu')->findOrFail($id);

        return view('omega::menus.builder', compact('menu'));
    }

    public function delete_menu($menu, $id)
    {
        Omega::canOrFail('delete_menus');

        $item = Omega::model('MenuItem')->findOrFail($id);

        $item->destroy($id);

        return redirect()
            ->route('omega.menus.builder', [$menu])
            ->with([
                'message'    => 'Successfully Deleted Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function add_item(Request $request)
    {
        Omega::canOrFail('add_menus');

        $data = $this->prepareParameters(
            $request->all()
        );

        $data['order'] = 1;

        $highestOrderMenuItem = Omega::model('MenuItem')->where('parent_id', '=', null)
            ->orderBy('order', 'DESC')
            ->first();

        if (!is_null($highestOrderMenuItem)) {
            $data['order'] = intval($highestOrderMenuItem->order) + 1;
        }

        Omega::model('MenuItem')->create($data);

        return redirect()
            ->route('omega.menus.builder', [$data['menu_id']])
            ->with([
                'message'    => 'Successfully Created New Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function update_item(Request $request)
    {
        Omega::canOrFail('edit_menus');

        $id = $request->input('id');
        $data = $this->prepareParameters(
            $request->except(['id'])
        );

        $menuItem = Omega::model('MenuItem')->findOrFail($id);
        $menuItem->update($data);

        return redirect()
            ->route('omega.menus.builder', [$menuItem->menu_id])
            ->with([
                'message'    => 'Successfully Updated Menu Item.',
                'alert-type' => 'success',
            ]);
    }

    public function order_item(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->orderMenu($menuItemOrder, null);
    }

    private function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
            $item = Omega::model('MenuItem')->findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }

    protected function prepareParameters($parameters)
    {
        switch (array_get($parameters, 'type')) {
            case 'route':
                $parameters['url'] = null;
                break;
            default:
                $parameters['route'] = null;
                $parameters['parameters'] = '';
                break;
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }
}
