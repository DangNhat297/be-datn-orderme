<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Dishes;
use Illuminate\Support\Facades\Validator;

class DishesController extends Controller
{
    public function __construct(protected Dishes $dishes)
    {
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
function store(Request $request)
{
    $Validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required',
        'price' => 'required',
        'quantity' => 'required',
        'category_id' => 'required',
        'image' => 'required|mimes:jpeg,png,jpg,gif',
    ]);

    if ($Validator->fails()) {
       return $this->createErrors($Validator->errors());
    }
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
public function update(Request $request, $id)
{

    $Validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required',
        'price' => 'required',
        'quantity' => 'required',
        'category_id' => 'required',
        'image' => 'required|mimes:jpeg,png,jpg,gif',
    ]);

    if ($Validator->fails()) {
    return $this->createErrors($Validator->errors());
    }

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
