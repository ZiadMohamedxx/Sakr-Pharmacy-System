<?php
require_once 'customer.php';
class Prescription
{
    public $Prescription_ID;
    public $customer_ID;
    public $Uploded_Date;
    public $Image_URL;
    public $Status;

    public function Upload()
    {
        $this->Uploded_Date=date('Y-m-d');
        $this->Status="Pending";

        if(!empty($this->Image_URL))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function Verify()
    {
        //lw el roshetta approved mn el doc 
        if($this->Status!=="Rejected")
        {
            $this->Status="Approved";
        }
    }

    public function Reject()
    {
        //mark en el roshetta rejected
        if($this->Status!=="Approved")
        {
            $this->Status="Rejected";
        }
    }




}