<?php
class TempCate
{
    public $cat_id;
    public $cat_title;
    public $numOfProduct;
    public function __construct($_cat_id, $_cat_title, $_numOfProduct)
    {
        $this->cat_id = $_cat_id;
        $this->cat_title = $_cat_title;
        $this->numOfProduct = $_numOfProduct;
    }
}
