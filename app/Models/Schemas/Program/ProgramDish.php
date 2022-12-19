<?php

namespace App\Models\Schemas\Program;
/**
 * @OA\Schema
 *     schema="ProgramDish",
 *     type="object",
 *     title="ProgramDish",
 *     description="Program model"
 * )
 */
class ProgramDish
{

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $dish_id;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $discount_percent;

}
