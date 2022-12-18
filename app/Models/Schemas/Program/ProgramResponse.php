<?php

namespace App\Models\Schemas\Program;
/**
 * @OA\Schema
 *     schema="ProgramResponse",
 *     type="object",
 *     title="ProgramResponse",
 *     description="Program model"
 * )
 */
class ProgramResponse
{

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;

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
     *      @OA\Items(ref="#/components/schemas/DishesResponse")
     * )
     *
     * @var array
     */
    public $dishes;

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

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $created_at;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $updated_at;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/DishItemFlashsale")
     * )
     *
     * @var array
     */
    public $dishes_flashSale;


    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $total_flashSale;
}
