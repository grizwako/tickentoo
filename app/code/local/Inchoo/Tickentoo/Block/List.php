<?php

//Bad to do extend like this? Should all of 'parent medthods' this go to helpers?
class Inchoo_Tickentoo_Block_List extends Inchoo_Tickentoo_Block_Tickets
{
    public function __construct()
    {
        parent::__construct();

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $websiteId = Mage::app()->getWebsite()->getId();
        $tickets = Mage::getModel('inchoo_tickentoo/ticket')->getCollection();
        $tickets->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('website_id', $websiteId)
            ->setOrder('closed', $tickets::SORT_ORDER_ASC);

        $this->setTickets($tickets);
        Mage::app()->getFrontController()->getAction()->getLayout()
            ->getBlock('root')
            ->setHeaderTitle($this->__('My Tickets'));
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'tickentoo.list.pager')
            ->setCollection($this->getTickets());
        $this->setChild('pager', $pager);
        $this->getTickets()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getAddNewTicketUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}