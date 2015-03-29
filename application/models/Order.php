<?php

/**
 * This is a model for burger Orders.
 *
 * @author Calvin
 */
class Order extends CI_Model {

    protected $xml = null;
    protected $customer;
    protected $type;
    protected $burgers = array();
    protected $special;

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    public function loadFile($file)
    {
        $this->xml = simplexml_load_file($file);
        $this->customer = (string) $this->xml->customer;
        $this->type = (string) $this->xml['type'];
        
        if (isset($this->xml->special))
        {
            $this->special = (string) $this->xml->special;
        }
        
        $i = 0;
        foreach ($this->xml->burger as $burger)
        {
            $record = array( 'num' => $i++ );
            
            $patty = $burger->patty['type'];
            $record['patty'] = $patty;
            
            $cheese = '';
            if (isset($burger->cheeses['top']))
            {
                $cheese = $burger->cheeses['top'];
            }
            if (isset($burger->cheeses['bottom']))
            {
                $cheese .= ' & ' . $burger->cheeses['bottom'];
            }
            $record['cheese'] = $cheese;
            
            $toppings = '';
            if (isset($burger->topping))
            {
                $toppings .= $burger->topping['type'] . ' ';
            }
            $record['toppings'] = $toppings;
            
            
            $sauces = '';
            if (isset($burger->sauce))
            {
                foreach ($burger->sauce as $sauce)
                {
                    $sauces .= $sauce['type'] . ' ';
                }
            }
            $record['sauces'] = $sauces;
            
            $instructions = '';
            if (!isset($burger->instructions))
            {
                $instructions = $burger->instructions;
            }
            $record['instructions'] = $instructions;
            $record['cost'] = 0;
            
            $this->burgers[] = $record;
        }
    }
    
    public function getOrderType()
    {
        return $this->type;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    public function getBurgers()
    {
        return $this->burgers;
    }
    
    public function getSpecial()
    {
        return $this->special;
    }
    
    public function getTotal()
    {
        $cost = 0;
        
        foreach ($this->burgers as $burger)
        {
            $cost += $burger['cost'];
        }
        
        return $cost;
    }
}
