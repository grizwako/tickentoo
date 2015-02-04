<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Edit_Tab_Reply extends Mage_Adminhtml_Block_Widget_Form
{
    public function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('ticket', [
            'legend' => 'Reply to customer'
        ]);

        $fieldset->addField('subject', 'textarea', [
            'name'  => 'message',
            'label' => $this->__('Message'),
            'title' => $this->__('Message'),
            'note'  => $this->__('Message'),
            'required'  => false
        ]);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html){
        $ticket = Mage::registry('current_ticket');
        if(!$ticket or !$ticket->getId()) {
            return $html;
        }
        $replies = $this->getLayout()->createBlock('core/template');
        $replies->assign('ticket', $ticket);
        $replies->setTemplate('inchoo/tickentoo/replies.phtml');
        return $replies->toHtml().$html;

    }
}