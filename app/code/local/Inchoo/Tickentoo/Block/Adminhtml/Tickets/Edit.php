<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inchoo_tickentoo';
        $this->_controller = 'adminhtml_tickets';
        $this->_mode = 'edit';
        $this->_headerText = 'new or edit...'; //TODO: fixme

        parent::__construct();
    }
}