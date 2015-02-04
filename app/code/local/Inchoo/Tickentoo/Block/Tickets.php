<?php

//TODO: refactor this methods in separate blocks?
//TODO: url generation refactor
class Inchoo_Tickentoo_Block_Tickets extends Mage_Core_Block_Template
{
    //TODO: think, implement
    public function getCustomerTickets()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $websiteId = Mage::app()->getWebsite()->getId();
        $tickets = Mage::getModel('inchoo_tickentoo/ticket')->getCollection();
        $tickets->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('website_id', $websiteId)
            ->setOrder('closed', $tickets::SORT_ORDER_ASC);
        return $tickets;
    }

    //TODO: implement
    public function getFormHtmlId()
    {
        return 'dummy_tickentoo';
    }

    public function getFormAction()
    {
        return $this->getUrl('*/*/save', ['ticketId' => $this->getTicketId()]);
    }

    public function getViewUrl($ticket)
    {
        return $this->getUrl('*/*/view', ['ticketId' => $ticket->getId()]);
    }

    public function getCloseUrl($ticket)
    {
        return $this->getUrl('*/*/close', ['ticketId' => $ticket->getId()]);
    }

    public function getOpenUrl($ticket)
    {
        return $this->getUrl('*/*/open', ['ticketId' => $ticket->getId()]);
    }

    public function getTicketId()
    {
        $ticket = $this->getTicket();
        if ($ticket) {
            return $ticket->getId();
        } else {
            return null;
        }
    }

    public function getTicket()
    {
        return Mage::registry('current_ticket');
    }

    public function getFormTitle()
    {
        if($this->getTicket()) {
            return 'Reply:';
        } else {
            return 'Create new ticket:';
        }
    }
}