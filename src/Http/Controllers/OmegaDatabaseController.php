<?php

namespace artworx\omegacp\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use artworx\omegacp\Database\DatabaseUpdater;
use artworx\omegacp\Database\Schema\Column;
use artworx\omegacp\Database\Schema\Identifier;
use artworx\omegacp\Database\Schema\SchemaManager;
use artworx\omegacp\Database\Schema\Table;
use artworx\omegacp\Database\Types\Type;
use artworx\omegacp\Facades\Omega;
use artworx\omegacp\Models\DataType;
use artworx\omegacp\Models\Permission;

class OmegaDatabaseController extends Controller
{
    public function index()
    {
        Omega::canOrFail('browse_database');

        $dataTypes = Omega::model('DataType')->select('id', 'name')->get()->pluck('id', 'name')->toArray();

        $tables = array_map(function ($table) use ($dataTypes) {
            $table = [
                'name'          => $table,
                'dataTypeId'    => isset($dataTypes[$table]) ? $dataTypes[$table] : null,
            ];

            return (object) $table;
        }, SchemaManager::listTableNames());

        return view('omega::tools.database.index')->with(compact('dataTypes', 'tables'));
    }

    public function create()
    {
        Omega::canOrFail('browse_database');

        $db = $this->prepareDbManager('create');

        return view('omega::tools.database.edit-add', compact('db'));
    }

    public function store(Request $request)
    {
        Omega::canOrFail('browse_database');

        try {
            Type::registerCustomPlatformTypes();

            $table = Table::make($request->table);
            SchemaManager::createTable($table);

            if (isset($request->create_model) && $request->create_model == 'on') {
                $params = [
                    'name' => Str::studly(Str::singular($table->name)),
                ];

                // if (in_array('deleted_at', $request->input('field.*'))) {
                //     $params['--softdelete'] = true;
                // }

                if (isset($request->create_migration) && $request->create_migration == 'on') {
                    $params['--migration'] = true;
                }

                Artisan::call('omega:make:model', $params);
            } elseif (isset($request->create_migration) && $request->create_migration == 'on') {
                Artisan::call('make:migration', [
                    'name'    => 'create_'.$table->name.'_table',
                    '--table' => $table->name,
                ]);
            }

            return redirect()
               ->route('omega.database.edit', $table->name)
               ->with($this->alertSuccess("Successfully created {$table->name} table"));
        } catch (Exception $e) {
            return back()->with($this->alertException($e))->withInput();
        }
    }

    public function edit($table)
    {
        Omega::canOrFail('browse_database');

        if (!SchemaManager::tableExists($table)) {
            return redirect()
                ->route('omega.database.index')
                ->with($this->alertError("The table you want to edit doesn't exist"));
        }

        $db = $this->prepareDbManager('update', $table);

        return view('omega::tools.database.edit-add', compact('db'));
    }

    /**
     * Update database table.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        Omega::canOrFail('browse_database');

        $table = json_decode($request->table, true);

        try {
            DatabaseUpdater::update($table);
            // TODO: synch BREAD with Table
            // $this->cleanOldAndCreateNew($request->original_name, $request->name);
        } catch (Exception $e) {
            return back()->with($this->alertException($e))->withInput();
        }

        return redirect()
               ->route('omega.database.edit', $table['name'])
               ->with($this->alertSuccess("Successfully updated {$table['name']} table"));
    }

    protected function prepareDbManager($action, $table = '')
    {
        $db = new \stdClass();

        // Need to get the types first to register custom types
        $db->types = Type::getPlatformTypes();

        if ($action == 'update') {
            $db->table = SchemaManager::listTableDetails($table);
            $db->formAction = route('omega.database.update', $table);
        } else {
            $db->table = new Table('New Table');
            $db->formAction = route('omega.database.store');
        }

        $oldTable = old('table');
        $db->oldTable = $oldTable ? $oldTable : json_encode(null);
        $db->action = $action;
        $db->identifierRegex = Identifier::REGEX;
        $db->platform = SchemaManager::getDatabasePlatform()->getName();

        return $db;
    }

    public function cleanOldAndCreateNew($originalName, $tableName)
    {
        if (!empty($originalName) && $originalName != $tableName) {
            $dt = DB::table('data_types')->where('name', $originalName);
            if ($dt->get()) {
                $dt->delete();
            }

            $perm = DB::table('permissions')->where('table_name', $originalName);
            if ($perm->get()) {
                $perm->delete();
            }

            $params = ['name' => Str::studly(Str::singular($tableName))];
            Artisan::call('omega:make:model', $params);
        }
    }

    public function reorder_column(Request $request)
    {
        Omega::canOrFail('browse_database');

        if ($request->ajax()) {
            $table = $request->table;
            $column = $request->column;
            $after = $request->after;
            if ($after == null) {
                // SET COLUMN TO THE TOP
                DB::query("ALTER $table MyTable CHANGE COLUMN $column FIRST");
            }

            return 1;
        }

        return 0;
    }

    public function show($table)
    {
        Omega::canOrFail('browse_database');

        return response()->json(SchemaManager::describeTable($table));
    }

    public function destroy($table)
    {
        Omega::canOrFail('browse_database');

        try {
            Schema::drop($table);

            return redirect()
                ->route('omega.database.index')
                ->with($this->alertSuccess("Successfully deleted $table table"));
        } catch (Exception $e) {
            return back()->with($this->alertException($e));
        }
    }

    /********** BREAD METHODS **********/

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBread(Request $request, $table)
    {
        Omega::canOrFail('browse_database');

        $data = $this->prepopulateBreadInfo($table);
        $data['fieldOptions'] = SchemaManager::describeTable($table);

        return view('omega::tools.database.edit-add-bread', $data);
    }

    private function prepopulateBreadInfo($table)
    {
        $displayName = Str::singular(implode(' ', explode('_', Str::title($table))));
        $modelNamespace = config('omega.models.namespace', app()->getNamespace());
        if (empty($modelNamespace)) {
            $modelNamespace = app()->getNamespace();
        }

        return [
            'table'                 => $table,
            'slug'                  => Str::slug($table),
            'display_name'          => $displayName,
            'display_name_plural'   => Str::plural($displayName),
            'model_name'            => $modelNamespace.Str::studly(Str::singular($table)),
            'generate_permissions'  => true,
            'server_side'           => false,
        ];
    }

    public function storeBread(Request $request)
    {
        Omega::canOrFail('browse_database');

        try {
            $dataType = Omega::model('DataType');
            $data = $dataType->updateDataType($request->all(), true)
                ? $this->alertSuccess('Successfully created new BREAD')
                : $this->alertError('Sorry it appears there may have been a problem creating this BREAD');

            return redirect()->route('omega.database.index')->with($data);
        } catch (Exception $e) {
            return redirect()->route('omega.database.index')->with($this->alertException($e, 'Saving Failed'));
        }
    }

    public function addEditBread($table)
    {
        Omega::canOrFail('browse_database');

        $dataType = Omega::model('DataType')->whereName($table)->first();

        try {
            $fieldOptions = isset($dataType) ? $dataType->fieldOptions() : SchemaManager::describeTable($dataType->name);
        } catch (Exception $e) {
            $fieldOptions = SchemaManager::describeTable($dataType->name);
        }

        return view(
            'omega::tools.database.edit-add-bread', [
                'dataType'     => $dataType,
                'fieldOptions' => $fieldOptions,
            ]
        );
    }

    public function updateBread(Request $request, $id)
    {
        Omega::canOrFail('browse_database');

        /* @var \artworx\omegacp\Models\DataType $dataType */
        try {
            $dataType = Omega::model('DataType')->find($id);

            $data = $dataType->updateDataType($request->all(), true)
                ? $this->alertSuccess("Successfully updated the {$dataType->name} BREAD")
                : $this->alertError('Sorry it appears there may have been a problem updating this BREAD');

            return redirect()->route('omega.database.index')->with($data);
        } catch (Exception $e) {
            return back()->with($this->alertException($e, 'Update Failed'));
        }
    }

    public function deleteBread($id)
    {
        Omega::canOrFail('browse_database');

        /* @var \artworx\omegacp\Models\DataType $dataType */
        $dataType = Omega::model('DataType')->find($id);
        $data = Omega::model('DataType')->destroy($id)
            ? $this->alertSuccess("Successfully removed BREAD from {$dataType->name}")
            : $this->alertError('Sorry it appears there was a problem removing this BREAD');

        if (!is_null($dataType)) {
            Omega::model('Permission')->removeFrom($dataType->name);
        }

        return redirect()->route('omega.database.index')->with($data);
    }
}
