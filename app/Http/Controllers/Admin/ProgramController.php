<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(
        protected Program $program
    ){
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = $request->per_page ?: PAGE_SIZE_DEFAULT;

        $programs = $this->program->newQuery()->paginate($page_size);

        return $this->sendSuccess($programs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramRequest $request)
    {
        $data = $request->only([
            'title',
            'description',
            'status',
            'discount_percent',
            'start_date',
            'end_date',
        ]);

        $dishesId = $request->dish_ids;

        $program = $this->program
            ->newQuery()
            ->create($data);
        $program->dishes()->attach($dishesId);

        return $this->createSuccess($program);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        return $this->sendSuccess($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramRequest $request, Program $program)
    {
        $data = $request->only([
            'title',
            'description',
            'status',
            'discount_percent',
            'start_date',
            'end_date',
        ]);

        $program->update($data);
        $dishIds = $request->dish_ids;
        $program->dishes()->sync($dishIds);

        return $this->updateSuccess($program);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return $this->deleteSuccess();
    }

    public function toggleStatus(Program $program)
    {
        $status = $program->status == ENABLE ? DISABLE : ENABLE;

        $program->update(['status' => $status]);

        return $this->updateSuccess($program);
    }
}
