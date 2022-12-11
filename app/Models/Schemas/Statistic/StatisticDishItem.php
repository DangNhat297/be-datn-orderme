<?php

namespace App\Models\Schemas\Statistic;

/**
 * @OA\Schema(
 *      schema="StatisticDishItem",
 *      title=" Dish Item",
 *      description=" model",
 *      type="object",
 * )
 *
 */
class StatisticDishItem
{
    /**
     * @OA\Property()
     *
     * @var number
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
     * @var number
     */
    public $price;
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $quantity_buy;
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $total;

}
