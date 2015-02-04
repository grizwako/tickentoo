<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inchoo_tickentoo';
        $this->_controller = 'adminhtml_tickets';
        $this->_headerText = '7!CK3N70';
        parent::__construct();
    }
}