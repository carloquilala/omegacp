<?php

namespace artworx\omegacp\Http\Controllers;

use Illuminate\Http\Request;
use artworx\omegacp\Facades\Omega;

class OmegaRoleController extends OmegaBreadController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        Omega::canOrFail('edit_roles');

        $slug = $this->getSlug($request);

        $dataType = Omega::model('DataType')->where('slug', '=', $slug)->first();

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        $data->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route("omega.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Updated {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }

    // POST BRE(A)D
    public function store(Request $request)
    {
        Omega::canOrFail('add_roles');

        $slug = $this->getSlug($request);

        $dataType = Omega::model('DataType')->where('slug', '=', $slug)->first();

        $data = new $dataType->model_name();
        $this->insertUpdateData($request, $slug, $dataType->addRows, $data);

        $data->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route("omega.{$dataType->slug}.index")
            ->with([
                'message'    => "Successfully Added New {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
    }
}
