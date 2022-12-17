<?php

namespace App\Models\Schemas\Statistic;
/**
 * @OA\Schema
 *     schema="DishItemFlashsale",
 *     type="object",
 *     title="DishItemFlashsale",
 *     description="Statistic model"
 * )
 */
class DishItemFlashsale
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
    public $name;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $price;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $image;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $quantity_buy;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $total;
}
