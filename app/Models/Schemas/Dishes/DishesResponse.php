<?php

namespace App\Models\Schemas\Dishes;

/**
 * @OA\Schema
 *     schema="DishesResponse",
 *     type="object",
 *     title="DishesResponse",
 *     description="Dishes model"
 * )
 */
class DishesResponse
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
     * @var number
     */
    public $price_sale;

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
     * @var string
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
     * @OA\Property()
     *
     * @var integer
     */
    private $id;
}
