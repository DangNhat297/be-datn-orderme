<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishesRequest;
use App\Http\Requests\DishesUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Dishes;
use Illuminate\Support\Facades\Validator;

class DishesController extends Controller
{
    protected $dishes;
    public function __construct( Dishes $dishes)
    {
        $this->dishes=$dishes;
    }

/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
public
function index()
{
    $data = $this->dishes
        ->newQuery()
        ->orderBy('id', 'DESC')
        ->paginate(PAGE_SIZE_DEFAULT);

    return $this->sendSuccess($data);
}

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
public
function create()
{
    //
}

/**
 * Store a newly created resource in storage.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
public
function store(DishesRequest $request)
{

    $item = $this->dishes->fill($request->all());
    if ($request->hasFile('image')) {
        $file = $request->image;
        $item->image =   uploadFile($file,'images/dishes/');;
    }

    $item->save();
    return $this->createSuccess($item);

}

/**
 * Display the specified resource.
 *
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public
function show($id)
{
    $item = $this->dishes
        ->newQuery()
        ->findOrFail($id);
    return $this->sendSuccess($item);
}

/**
 * Show the form for editing the specified resource.
 *
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public
function edit($id)
{
    //
}

/**
 * Update the specified resource in storage.
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public function update(DishesUpdateRequest $request, $id)
{

    $item = $this->dishes->findOrFail($id);
    $item ->fill($request->except(['image']));

    if ($request->image) {
        $file = $request->image;
        $fileCurrent = public_path() .'/'. $item->image;
        if (file_exists($item->image)) {
            unlink($fileCurrent);
        }
        $item->image = uploadFile($file,'images/dishes/');;
    }else{
        $item->image= $item->image ;
    }
    $item->save();

    return $this->sendSuccess($item);
}

/**
 * Remove the specified resource from storage.
 *
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    $item = $this->dishes
        ->newQuery()
        ->findOrFail($id);
    $fileCurrent = public_path() .'/'. $item->image;
    if (file_exists($item->image)) {
        unlink($fileCurrent);
    }
    $item->delete();

    return $this->deleteSuccess();
}




}
