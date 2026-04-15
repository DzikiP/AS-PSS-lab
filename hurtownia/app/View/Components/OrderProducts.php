<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OrderProducts extends Component
{
    public $products;
    public $mode;
    public $productsData;

    public function __construct($products, $mode = 'edit', $productsData = [])
    {
        $this->products = $products;
        $this->mode = $mode;
        $this->productsData = $productsData;
    }

    public function render()
    {
        return view('components.order-products');
    }
}
