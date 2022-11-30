<?php

namespace App\Models\Schemas\User;
/**
 * @OA\Schema
 *     schema="UserResponse",
 *     type="object",
 *     title="UserResponse",
 *     description="User model"
 * )
 */
class UserResponse
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
    public $phone;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $email;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $avatar;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;
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
