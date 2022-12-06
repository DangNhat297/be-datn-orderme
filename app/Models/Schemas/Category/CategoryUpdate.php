<?php

namespace App\Models\Schemas\Category;
/**
 * @OA\Schema
 *     schema="CategoryUpdate",
 *     type="object",
 *     title="CategoryUpdate",
 *     description="Category model"
 * )
 */
class CategoryUpdate
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
     * @var string
     */
    public $image;

//    /**
//     * @OA\Property()
//     *
//     * @var integer
//     */
//    public $parent_id;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;
}
