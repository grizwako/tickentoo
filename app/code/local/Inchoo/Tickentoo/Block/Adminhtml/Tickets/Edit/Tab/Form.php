<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $ticket = $this->_getTicket();
        $fieldset = $form->addFieldset('ticket', [
            'legend' => 'Ticket information'
        ]);

        $fieldset->addField('subject', 'text', [
            'name'  => 'subject',
            'label' => $this->__('Subject'),
            'title' => $this->__('Subject'),
            'note'  => $this->__('Enter descriptive subject!'),
            'class' => 'required-entry',
            'required'  => true
        ]);

        $fieldset->addField('created_at', 'text', [
            'label'     => $this->__('Creation time'),
            'readonly'  => true,
            'name'      => 'created_at'
        ]);

        $form->setValues($ticket);

        $customerName = $ticket->getCustomerName();
        $fieldset->addField('customer', 'text', [
            'label'     => $this->__('customer'),
            'readonly'  => true,
            'name'      => 'customer',
            'value'     => $customerName
        ]);

        return parent::_prepareForm();
    }

    protected function _getTicket()
    {
        if(!$this->hasData('ticket')) {
            $ticket = Mage::registry('current_ticket');
            if (!$ticket instanceof Inchoo_Tickentoo_Model_Ticket) {
                $ticket = Mage::getModel('inchoo_tickentoo/ticket');
            }
            $this->setData('ticket', $ticket);
        }
        return $this->getData('ticket');
    }
}