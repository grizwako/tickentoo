<?php

class Inchoo_Tickentoo_Block_Adminhtml_Tickets_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form([
            'id' => 'edit_form',
            'action' => $this->getUrl(
                '*/*/edit',
                [
                    '_current' => true,
                    'continue' => 0
                ]
            ),
            'method' => 'post'
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}