<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
	// Build a list of orders
        $this->load->helper('directory');
        $map = directory_map('data/', 1);
        $orders = array();
        
        // Generate array of XML files
        foreach ($map as $order)
        {
            $fileparts = pathinfo($order);
            if ($fileparts['extension'] == 'xml' && $fileparts['basename'] != 'menu.xml')
            {
                $orders[] = array(
                    'filename' => substr($fileparts['basename'], 0, strlen($fileparts['basename']) - 4)
                );
            }
        }
	
	// Present the list to choose from
        $this->data['orders'] = $orders;
	$this->data['pagebody'] = 'homepage';
	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
	// Build a receipt for the chosen order
        $this->load->model('order');
        $this->order->loadFile(DATAPATH . $filename . '.xml');
        
        $this->data['num'] = ucfirst($filename);
        $this->data['name'] = $this->order->getCustomer();
        $this->data['type'] = $this->order->getOrderType();
        $this->data['burgers'] = $this->order->getBurgers();
        $this->data['special'] = $this->order->getSpecial();
        $this->data['total'] = $this->order->getTotal();
	
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
