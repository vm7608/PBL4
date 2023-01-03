<?php
class TempUser
{
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $mobile;
    public $address1;
    public $address2;

    public function __construct($_user_id, $_first_name, $_last_name, $_email, $_mobile, $_address1, $_address2)
    {
        $this->user_id = $_user_id;
        $this->first_name = $_first_name;
        $this->last_name = $_last_name;
        $this->email = $_email;
        $this->mobile = $_mobile;
        $this->address1 = $_address1;
        $this->address2 = $_address2;
    }
}
