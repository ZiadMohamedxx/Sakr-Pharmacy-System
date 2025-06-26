<?php
require_once 'user.php';
require_once 'ObserverInterface.php';
class Notification extends ObserverInterface
{
    public $Notification_ID;
    public $User_ID;
    public $Message;
    public $Date_Sent;
    public $IsRead;

    public function MarkAsRead()
    {
        $this->IsRead=true;

    }

    public function Send()
    {
        if(!empty($this->Message))
        {
            $this->Date_Sent=date('Y-m-d');
            $this->IsRead=false;
            return true;
        }
        else
        {
            return false;
        }
    }

    // code el observer 
    public function update($message, $user_id)
    {
        $this->Message = $message;
        $this->User_ID = $user_id;
        $this->Date_Sent = date('Y-m-d');
        $this->IsRead = false;

        //TEST
        echo "<div class='result'>🔔 Notification: {$this->Message} (to User ID: {$this->User_ID})</div>";
    }

}