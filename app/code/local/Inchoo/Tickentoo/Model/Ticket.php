<?php

class Inchoo_Tickentoo_Model_Ticket extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('inchoo_tickentoo/ticket');
    }

    protected function fillCustomerId() {
        $this->setCustomerId(
            Mage::getSingleton('customer/session')->getCustomer()->getId()
        );
    }

    protected function fillWebsiteId() {
        $this->setWebsiteId(Mage::app()->getWebsite()->getId());
    }

    //TODO: fill not here, can not couple frontend stuff to db model
    public function fillTicket($subject) {
        $this->fillCustomerId();
        $this->fillWebsiteId();
        $this->fillCreatedAt();
        $this->setSubject($subject);
    }

    public function fillCreatedAt(){
        $this->setCreatedAt(Varien_Date::now());
    }

    public function getReplies()
    {
        $replies = Mage::getModel('inchoo_tickentoo/reply')->getCollection();
        $replies->addFieldToFilter('ticket_id', $this->getId());
        return $replies;
    }


    public function makeReply($message, $authorType, $authorId)
    {
        if(!$this->getId()){
            throw new Exception('Ticket must be saved before creating reply');
        }
        $reply = Mage::getModel('inchoo_tickentoo/reply');
        $reply->setTicketId($this->getId());
        $reply->setMessage($message);
        $reply->setAuthorType($authorType);
        $reply->setAuthorId($authorId);

        return $reply;
    }

    //TODO: cache name as dummy attribute/data/smth
    public function getCustomerName()
    {
        $user = Mage::getModel('customer/customer')
            ->load($this->getCustomerId());
        if(!$user->getId()){
            //TODO: add foreign key ticket.customer_id
            return null;
        }
        return $user->getName();
    }


}