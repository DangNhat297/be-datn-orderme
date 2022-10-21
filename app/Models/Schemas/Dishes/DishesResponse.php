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
class DishesResponse{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $id;

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
     * @var integer
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
}
