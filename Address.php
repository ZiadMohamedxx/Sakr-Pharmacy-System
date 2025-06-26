<?php
require_once'customer.php';
class Address
{
    public $customer_ID;
    public $Street;
    public $City;
    public $PostalCode;
    public $Country;

    public function AddAddress()
    {
        if(!empty($this->Street)&&!empty($this->City)&&!empty($this->PostalCode)&&!empty($this->Country))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function UpdateAddress()
    {
        if(!empty($this->Street)||!empty($this->City)||!empty($this->PostalCode)||!empty($this->Country))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function DeleteAddress()
    {
        $this->Street=null;
        $this->City=null;
        $this->PostalCode=null;
        $this->Country=null;
        return true;

    }




}