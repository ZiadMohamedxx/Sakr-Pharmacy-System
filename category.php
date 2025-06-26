<?php

class Category
{
    public $categoryName;
    public $category_description;

    public function checkStock()
    {
        $name = $this->categoryName;

        
        if (!empty($name)) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function updateCategory($newName=null,$newdescription=null)
    {
        $desc = $this->category_description;

        if ($newName !== null) 
        {
            $this->categoryName = $newName;
        }

        if ($newdescription !== null)
        {
            $this->category_description = $newdescription;
        }
    }

    public function deleteCategory()
    {
        // hakhlehom kolohom b null 
        $this->categoryName = null;
        $this->category_description = null;
    }
}


?>