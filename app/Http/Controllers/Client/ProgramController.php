<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Program;

class ProgramController extends Controller
{
    public function __construct(protected Program $program)
    {
    }

    public function index(){
        $program = $this->program
            ->newQuery()
            ->where('status', ENABLE)
            ->get()
            ->load('dishes');

        return $this->sendSuccess($program);
    }

    public function show(){
        $program = $this->program
                ->newQuery()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('status', ENABLE)
                ->firstOrFail()
                ->load('dishes');

        return $this->sendSuccess($program);
    }
}
