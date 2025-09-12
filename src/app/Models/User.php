<?php
class User
{
    public $id;
    public $name;
    public $address;
    public $email;
    public $password;

    public function __construct($id, $name,  $address, $email, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
        $this->password = $password;
    }
}
