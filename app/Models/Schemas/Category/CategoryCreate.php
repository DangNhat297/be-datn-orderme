<?php

namespace App\Models\Schemas\Category;
/**
 * @OA\Schema
 *     schema="CategoryCreate",
 *     type="object",
 *     title="CategoryCreate",
 *     description="Category model",
 * )
 */
class CategoryCreate
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
