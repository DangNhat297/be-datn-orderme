<?php

namespace App\Models\Schemas\Statistic;
/**
 * @OA\Schema
 *     schema="StatisticCategoryResponse",
 *     type="object",
 *     title="StatisticCategoryResponse",
 *     description="Statistic model"
 * )
 */
class StatisticCategoryResponse
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
     * @var string
     */
    public $dishes_count;
}
