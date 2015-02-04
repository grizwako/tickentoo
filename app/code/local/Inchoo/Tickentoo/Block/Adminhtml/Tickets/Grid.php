<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('inchoo_tickentoo/ticket_collection');
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

        return parent::_prepareColumns();
    }
}