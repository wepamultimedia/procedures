<?php

namespace Wepa\Procedures\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Backend\InertiaController;
use Wepa\Procedures\Http\Requests\CategoryRequest;
use Wepa\Procedures\Http\Requests\ProcedureRequest;
use Wepa\Procedures\Http\Resources\ProcedureResource;
use Wepa\Procedures\Models\Category;
use Wepa\Procedures\Models\Procedure;

class ProcedureController extends InertiaController
{
    public string $packageName = 'procedures';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $categories = Category::fillables();
        $procedures = Procedure::orderBy('position')->groupBy(['category_id'])->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%$search%");
        })
            ->when($request->category_id, function ($categoryId, $query) {
                $query->whereCategoryId($categoryId);
            })
            ->orderBy('position')
            ->paginate();

        $procedures = ProcedureResource::collection($procedures);

        return $this->render('Vendor/Procedures/Backend/Procedure/Index', ['procedure', 'category'], compact(['procedures', 'categories']));
    }

    public function position(Procedure $procedure, int $position): RedirectResponse|Application|Redirector
    {
        $procedure->updatePosition($position, ['category_id' => $procedure->category_id]);

        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): Redirector|RedirectResponse|Application
    {
        $data = collect($request->all())
            ->merge([
                'position' => Procedure::nextPosition(),
            ])
            ->toArray();

        if (Category::hasChildren($data['category_id'])) {
            $category = Category::find($data['category_id']);

            return back()->withErrors(['category_id.hasChildren', trans(['category_id' => trans('procedures::category.has_children', ['category' => $category->name])])]);
        }

        $procedure = Procedure::create($data);
        $procedure->touch();

        return redirect(session()->has('backUrl')
            ? session()->get('backUrl')
            : route('admin.procedure.procedures.index'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        session()->flash('backUrl', url()->previous());
        $categories = Category::fillables();

        return $this->render('Vendor/Procedures/Backend/Procedure/Create', ['category', 'procedure'], compact(['categories']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Procedure $procedure): Response
    {
        session()->flash('backUrl', url()->previous());
        $procedure = ProcedureResource::make($procedure)->resolve();

        $categories = Category::fillables();

        return $this->render('Vendor/Procedures/Backend/Procedure/Edit', ['procedure', 'category'], compact(['procedure', 'categories']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProcedureRequest $request, Procedure $procedure): Redirector|RedirectResponse|Application
    {
        $params = collect($request->all())
            ->except(['translations'])
            ->toArray();

        $procedure->update($params);
        $procedure->touch();

        return redirect(session()->has('backUrl')
            ? session()->get('backUrl')
            : route('admin.procedures.procedures.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedure $procedure): Redirector|RedirectResponse|Application
    {
        $procedure->updatePosition($procedure::lastPosition(['category_id' => $procedure->category_id]));
        $procedure->delete();

        return back();
    }
}
