<?php

namespace App\Models\Schemas\Statistic;
/**
 * @OA\Schema
 *     schema="StatisticTopProductResponse",
 *     type="object",
 *     title="StatisticTopProductResponse",
 *     description="Statistic model"
 * )
 */
class StatisticTopProductResponse
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $users;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $dishes;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $categories;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $orders;
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/StatisticDishItem")
     * )
     *
     * @var array
     */
    public $topSelling;
}
