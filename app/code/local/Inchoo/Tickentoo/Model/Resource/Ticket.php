<?php

class Inchoo_Tickentoo_Model_Resource_Ticket extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('inchoo_tickentoo/ticket', 'ticket_id');
    }
}