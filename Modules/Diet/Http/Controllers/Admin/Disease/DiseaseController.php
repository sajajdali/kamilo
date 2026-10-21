<?php
namespace Modules\Diet\Http\Controllers\Admin\Disease;

use App\Base\AdminController;
use App\Base\Authorizable;
use Illuminate\Http\Request;
use Modules\Diet\Entities\Disease\Disease;

class DiseaseController extends AdminController
{

    use Authorizable;
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = 'بیماری ها';
        $diseases = Disease::paginate(10);

        return view('diet::admin.disease.index', compact('diseases', 'title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $title = 'افزودن بیماری جدید';

        return view('diet::admin.disease.create', compact('title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        //var_dump($request->is_special);exit;
        //Disease::create(['name' => $request->name, 'is_special' => (boolean)$request->is_special]);
        $disease = new Disease();
        $disease->name = $request->name;
        $disease->is_special = $request->is_special ? $request->is_special : 0;
        $disease->save();

        return redirect(route('admin.disease.index'));
    }

    /**
     * @param Request $request
     * @param Disease $disease
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Disease $disease)
    {
        $title = 'ویرایش بیماری';

        return view('diet::admin.disease.edit', compact('disease', 'title'));
    }

    /**
     * @param Request $request
     * @param Disease $disease
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Disease $disease)
    {
        $disease->name = $request->name;
        $disease->is_special = $request->is_special ? $request->is_special : 0;
        $disease->save();

        return redirect(route('admin.disease.index'));
    }

    /**
     * @param Request $request
     * @param Disease $disease
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, Disease $disease)
    {
        $disease->delete();

        return redirect(route('admin.disease.index'));
    }
}
