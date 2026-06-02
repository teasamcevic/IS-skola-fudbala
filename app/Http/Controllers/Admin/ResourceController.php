<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

abstract class ResourceController extends Controller
{
    protected string $model;
    protected string $title;
    protected string $routeBase;
    protected array $with = [];
    protected array $columns = [];
    protected array $fields = [];
    protected array $rules = [];

    public function index()
    {
        $records = $this->query()->latest('id')->paginate(15);

        return view('admin.resource.index', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'columns' => $this->columns,
            'records' => $records,
        ]);
    }

    public function create()
    {
        return view('admin.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'record' => null,
            'fields' => $this->fields(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $this->model::create($data);

        return redirect()->route($this->routeBase.'.index')->with('success', 'Podaci su sačuvani.');
    }

    public function edit($id)
    {
        $record = $this->query()->findOrFail($id);

        return view('admin.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'record' => $record,
            'fields' => $this->fields(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = $this->query()->findOrFail($id);
        $record->update($this->validatedData($request, $record));

        return redirect()->route($this->routeBase.'.index')->with('success', 'Podaci su ažurirani.');
    }

    public function destroy($id)
    {
        $this->query()->findOrFail($id)->delete();

        return redirect()->route($this->routeBase.'.index')->with('success', 'Zapis je obrisan.');
    }

    protected function query(): Builder
    {
        return $this->model::query()->with($this->with);
    }

    protected function fields(): array
    {
        return $this->fields;
    }

    protected function validatedData(Request $request, $record = null): array
    {
        $data = $request->validate($this->rules);

        foreach ($this->fields() as $name => $field) {
            if (($field['type'] ?? '') === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }
        }

        return Arr::only($data, array_keys($this->fields()));
    }
}
