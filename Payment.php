<?php
require_once 'order.php';
class Payment
{
    public $Payment_ID;
    public $Order_ID;
    public $TotalAmount;
    public $Status;
    public $PaymentDate;

    public function ProcessPayment()
    {
        if($this->TotalAmount>0)
        {
            $this->Status="Completed";
            $this->PaymentDate=date('Y-m-d');
            return true;
        }
        else
        {
            return false;
        }
    }

    public function RefundPayment()
    {
        if($this->Status==="completed")
        {
            $this->Status="Refunded";
            return true;
        }
        else
        {
            return false;

        }
    }

    public function CheckStatus()
    {
        return $this->Status;
    }


}