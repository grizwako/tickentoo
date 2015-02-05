<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        /** @var Inchoo_Tickentoo_Model_Resource_Ticket_Collection $collection */
        $collection = Mage::getModel('inchoo_tickentoo/ticket')->getCollection();
        $collection->addCustomerNameJoin();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['ticketId' => $row->getId()]);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('ticket_id', [
            'header'    => 'Ticket #',
            'index'     => 'ticket_id',
            'type'      => 'number'
        ]);

        $this->addColumn('subject', [
            'header'    => 'Subject',
            'index'     => 'subject'
        ]);

        $this->addColumn('closed', [
            'header'    => 'Closed',
            'index'     => 'closed'
        ]);

        $this->addColumn('customer_name', [
            'header'    => 'Customer Name',
            'index'     => 'customer_name'
        ]);
        return parent::_prepareColumns();
    }

    public function _prepareMassAction(){
        $this->setMassactionIdField('ticket_id');
        $this->getMassactionBlock()->setFormFieldName('ticket_id');
        $this->getMassactionBlock()->addItem('close', array(
            'label'=> Mage::helper('inchoo_tickentoo')->__('Close'),
            'url' => $this->getUrl('*/*/massClose'),
            'confirm' => Mage::helper('inchoo_tickentoo')->__('Close all selected tickets?')
        ));
    }
}