<?php

namespace App\Models\Schemas\Dishes;

/**
 * @OA\Schema
 *     schema="DishesUpdate",
 *     type="object",
 *     title="DishesUpdate",
 *     description="Dishes model"
 * )
 */
class DishesUpdate
{
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
    public $slug;

    /**
     * @OA\Property()
     *
     * @var number
     */
    public $price;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $content;

    /**
     * @OA\Property()
     *
     * @var object
     */
    public $image;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $quantity;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $category_id;
}
