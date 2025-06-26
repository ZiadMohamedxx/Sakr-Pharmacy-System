<?php
require_once 'Product.php';
class Inventory 
{

private $inventory_id;
public $Product_id;
public $quantity;

private static array $stock = [];

public function updateStock()
{
    //h3mel update ll stock 3n taree2 eni ha equal el "quantity" bel "stock"
    $this->stock[$this->Product_id] = $this->quantity;
}
public function getInventoryStatus()
{
    $inventory= $this->inventory_id;
    
   //3lashan lw mafesh ay product fel array akhleha b zero
    $count = Inventory::$stock[$this->Product_id] ?? 0;

    if ($count > 0) 
    {
        return true;
    }
    else 
    {
        return false;
    }
}

}






?>