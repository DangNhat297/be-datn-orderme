<?php
namespace App\Models\Schemas\User;

/**
 * @OA\Schema(
 *      schema="UserRegister",
 *      title="User register request",
 *      description="User register request body data",
 *      type="object",
 *      required={"name","email","password"},
 *      @OA\Xml(name="UserRegister")
 * )
 *
 */
class UserRegister
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
    public $password;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $password_confirmation;
}

?>
