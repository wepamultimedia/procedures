<?php

namespace Wepa\Procedures\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Backend\InertiaController;
use Wepa\Procedures\Http\Requests\CategoryRequest;
use Wepa\Procedures\Http\Resources\CategoryResource;
use Wepa\Procedures\Models\Category;

class CategoryController extends InertiaController
{
    public string $packageName = 'procedures';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $categories = Category::orderBy('position')
            ->whereNull('parent_id')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })->paginate();
        $categories = CategoryResource::collection($categories);

        return $this->render('Vendor/Procedures/Backend/Category/Index', ['procedure', 'category'], compact(['categories']));
    }

    public function position(Category $category, int $position): RedirectResponse|Application|Redirector
    {
        $category->updatePosition($position, ['parent_id' => $category->parent_id]);

        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): Redirector|RedirectResponse|Application
    {
        $data = collect($request->all())
            ->merge([
                'position' => Category::nextPosition(['parent_id' => $request->parent_id]),
            ])
            ->merge($request->translations)
            ->except(['translations'])
            ->toArray();

        if ($data['parent_id']) {
            if (Category::hasProcedures($data['parent_id'])) {
                return back()->withErrors(['name' => trans('procedures::category.has_procedures', ['category' => $data['name']])]);
            }
        }

        $category = Category::create($data);
        $category->touch();

        return redirect(session()->has('backUrl')
            ? session()->get('backUrl')
            : route('admin.procedures.categories.index'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Category $parent = null): Response
    {
        session()->flash('backUrl', url()->previous());
        $category = new Category(['parent_id' => $parent?->id]);
        $parents = Category::getParents($category->toArray());

        return $this->render('Vendor/Procedures/Backend/Category/Create', ['procedure', 'category'], compact(['parent', 'parents']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): Response
    {
        $categories = Category::orderBy('position')->whereParentId($category->id)->paginate();
        $parents = Category::getParents($category->toArray());
        $hasProcedures = Category::hasProcedures($category->id);
        $category = CategoryResource::make($category)->resolve();
        $categories = CategoryResource::collection($categories);

        return $this->render('Vendor/Procedures/Backend/Category/Edit',
            ['procedure', 'category'],
            compact(['category', 'categories', 'parents', 'hasProcedures']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): Redirector|RedirectResponse|Application
    {

        $params = collect($request->all())
            ->merge($request->translations)
            ->except(['translations'])
            ->toArray();

        $category->update($params);
        $category->touch();


        if($category->parent_id){
            $backUrl = route('admin.procedures.categories.edit', ['category' => $category->parent_id]);
        } else {
            $backUrl = route('admin.procedures.categories.index');
        }

        return redirect($backUrl);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Redirector|RedirectResponse|Application
    {
        $category->updatePosition($category::lastPosition(['parent_id' => $category->parent_id]));
        $category->delete();

        return back();
    }


}
