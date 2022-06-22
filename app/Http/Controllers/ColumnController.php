<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Http\Requests\StoreColumnRequest;
use App\Http\Requests\UpdateColumnRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreColumnRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug'  => Str::slug($request->title),
            'order' => Column::max('order') + 1
        ]);

        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'slug'  => ['required'],
            'order' => ['required', 'numeric']
        ]);
        return Column::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function show(Column $column)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function edit(Column $column)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateColumnRequest  $request
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateColumnRequest $request, Column $column)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Column::findOrFail($id)->delete();
        return response()->json("success");
    }
}
