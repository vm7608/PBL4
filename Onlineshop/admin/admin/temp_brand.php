<?php
class TempBrand
{
    public $brand_id;
    public $brand_title;
    public $numOfProduct;
    public function __construct($_brand_id, $_brand_title, $_numOfProduct)
    {
        $this->brand_id = $_brand_id;
        $this->brand_title = $_brand_title;
        $this->numOfProduct = $_numOfProduct;
    }
}
