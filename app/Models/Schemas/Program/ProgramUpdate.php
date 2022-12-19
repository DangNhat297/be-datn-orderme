<?php

namespace App\Models\Schemas\Program;
/**
 * @OA\Schema
 *     schema="ProgramUpdate",
 *     type="object",
 *     title="ProgramUpdate",
 *     description="Program model"
 * )
 */
class ProgramUpdate
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $title;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $discount_percent;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $start_date;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $end_date;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ProgramDish")
     * )
     *
     * @var array
     */
    public $dish_ids;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $banner;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;

}
