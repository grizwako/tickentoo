<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('tickentoo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Manage ticket');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('ticket_main', [
            'label' => 'Main data',
            'title' => 'Main data',
            'content' => $this->getLayout()
                ->createBlock('inchoo_tickentoo/adminhtml_tickets_edit_tab_form')
                ->toHtml()
        ]);

        $this->addTab('ticket_replies', [
            'label'     => 'Replies',
            'title'     => 'Replies',
            'content'   => $this->getLayout()
                ->createBlock('inchoo_tickentoo/adminhtml_tickets_edit_tab_reply')
                ->toHtml()
        ]);

        return parent::_beforeToHtml();
    }
}