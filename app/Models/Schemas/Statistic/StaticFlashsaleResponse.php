<?php

namespace App\Models\Schemas\Statistic;
/**
 * @OA\Schema
 *     schema="StaticFlashsaleResponse",
 *     type="object",
 *     title="StaticFlashsaleResponse",
 *     description="Statistic model"
 * )
 */
class StaticFlashsaleResponse
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
     * @var integer
     */
    public $status;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $title;

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
     * @OA\Property()
     *
     * @var integer
     */
    public $total_flashSale;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/DishItemFlashsale")
     * )
     *
     * @var array
     */
    public $dish_in_flashsale;
}
