<?php

namespace App\Models\Schemas\Statistic;
/**
 * @OA\Schema
 *     schema="StatisticResponse",
 *     type="object",
 *     title="StatisticResponse",
 *     description="Statistic model"
 * )
 */
class StatisticResponse
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $duration;
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/StatisticDishItem")
     * )
     *
     * @var array
     */
    public $dishes;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $total_money;
}
