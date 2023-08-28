<?php

namespace Wepa\Procedures\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wepa\Procedures\Models\ProcedureFile;

class ProcedureFileController extends Controller
{
    public function destroy(ProcedureFile $file)
    {
        $file->delete();

        return redirect()->back();
    }

    public function store(Request $request)
    {
        ProcedureFile::create($request->all());

        return redirect()->back();
    }

    public function update(Request $request, ProcedureFile $file)
    {
        $file->update($request->all());

        return redirect()->back();
    }
}
