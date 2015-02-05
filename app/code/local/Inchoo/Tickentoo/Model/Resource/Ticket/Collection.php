<?php

class Inchoo_Tickentoo_Model_Resource_Ticket_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{



    public function _construct()
    {
        $this->_init('inchoo_tickentoo/ticket');

    }

    public function addCustomerNameJoin()
    {
        $firstNameId = Mage::getModel('eav/entity_attribute')
            ->loadByCode('1', 'firstname')->getAttributeId();
        $middleNameId = Mage::getModel('eav/entity_attribute')
            ->loadByCode('1', 'middlename')->getAttributeId();
        $lastNameId = Mage::getModel('eav/entity_attribute')
            ->loadByCode('1', 'lastname')->getAttributeId();


        $this->getSelect()
            ->join(
                ['ce1' => 'customer_entity_varchar'],
                'ce1.entity_id=main_table.customer_id',
                'value'
            )
            ->where('ce1.attribute_id='.$firstNameId)
            ->joinLeft(
                ['ce2' => 'customer_entity_varchar'],
                'ce2.entity_id=main_table.customer_id AND ce2.attribute_id = '.$middleNameId,
                'value'
            )
            ->join(
                ['ce3' => 'customer_entity_varchar'],
                'ce3.entity_id=main_table.customer_id',
                'value'
            )
            ->where('ce3.attribute_id='.$lastNameId)
            ->columns(
                new Zend_Db_Expr("CONCAT_WS(' ', ce1.value, ce2.value, ce3.value) AS customer_name")
            );
    }

}