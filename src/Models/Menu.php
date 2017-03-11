<?php

namespace AI\Omega\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use AI\Omega\Facades\Omega;

/**
 * @todo: Refactor this class by using something like MenuBuilder Helper.
 */
class Menu extends Model
{
    protected $table = 'menus';
    
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Omega::modelClass('MenuItem'));
    }

    public function parent_items()
    {
        return $this->hasMany(Omega::modelClass('MenuItem'))
            ->whereNull('parent_id');
    }

    /**
     * Display menu.
     *
     * @param string      $menuName
     * @param string|null $type
     * @param array       $options
     *
     * @return string
     */
    public static function display($menuName, $type = null, array $options = [])
    {
        // GET THE MENU - sort collection in blade
        $menu = static::where('name', '=', $menuName)
            ->with('parent_items.children')
            ->first();

        // Check for Menu Existence
        if (!isset($menu)) {
            return false;
        }

        event('omega.menu.display', $menu);

        // Convert options array into object
        $options = (object) $options;

        // Set static vars values for admin menus
        if (in_array($type, ['admin', 'admin_menu'])) {
            $permissions = Omega::model('Permission')->all();
            $dataTypes = Omega::model('DataType')->all();
            $prefix = trim(route('omega.dashboard', [], false), '/');
            $user_permissions = null;

            if (!Auth::guest()) {
                $user = Omega::model('User')->find(Auth::id());
                $user_permissions = $user->role->permissions->pluck('key')->toArray();
            }

            $options->user = (object) compact('permissions', 'dataTypes', 'prefix', 'user_permissions');

            // change type to blade template name - TODO funky names, should clean up later
            $type = 'omega::menu.'.$type;
        } else {
            if (is_null($type)) {
                $type = 'omega::menu.default';
            } elseif ($type == 'bootstrap' && !view()->exists($type)) {
                $type = 'omega::menu.bootstrap';
            }
        }

        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($type, ['items' => $menu->parent_items, 'options' => $options])->render()
        );
    }
}
