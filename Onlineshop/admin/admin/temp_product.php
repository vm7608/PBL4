<?php
class TempProduct
{
    public $product_id;
    public $image;
    public $product_name;
    public $price;

    public function __construct($_product_id, $_image, $_product_name, $_price)
    {
        $this->product_id = $_product_id;
        $this->image = $_image;
        $this->product_name = $_product_name;
        $this->price = $_price;
    }
}
