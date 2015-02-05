<?php

class Inchoo_Tickentoo_IndexController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            Mage::getSingleton('customer/session')->addError($this->__('Please login to continue.'));
            $currentUrl = Mage::helper('core/url')->getCurrentUrl();
            Mage::getSingleton('customer/session')->setBeforeAuthUrl($currentUrl);
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    //TODO: sanitize? -> not here, when outputting?/xss protection in by default?
    //think about refactoring -> extract to models/helpers?
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $ticket = Mage::getModel('inchoo_tickentoo/ticket');
        $newTicket = false;
        if(isset($params['ticketId'])) {
            $ticket->load($params['ticketId']);
        } else {
            $ticket->fillTicket($params['subject']);
            $ticket->save();
            $newTicket = true;
        }
        $reply = Mage::getModel('inchoo_tickentoo/reply');
        $reply->setTicketId($ticket->getId());
        $reply->setMessage($params['message']);
        $reply->setAuthorType('customer');
        $reply->setAuthorId(
            Mage::getSingleton('customer/session')->getCustomer()->getId()
        );
        $reply->save();
        if($newTicket and
            Mage::getStoreConfig('tickentoo_config/tickentoo_email_notifications/notify_admin_on_new_ticket'))
        {
            $this->sendNotificationEmailToAdmin($ticket, $reply);
        }
        $this->_redirect('*/*/view', ['ticketId' => $ticket->getId()]);
    }

    public function viewAction()
    {
        $ticket = $this->getTicket();
        if(is_null($ticket)){
            return;
        }
        Mage::register('current_ticket', $ticket);
        $this->loadLayout();
        $this->renderLayout();
    }

    //require url param in func declaration?
    public function closeAction()
    {
        $ticket = $this->getTicket();
        if(is_null($ticket)){
            return;
        }
        $ticket->setClosed(1);
        $ticket->save();
        $this->_redirect('*/*/view', ['ticketId' => $ticket->getId()]);
    }

    //TODO: same code as closeAction, refactor
    public function openAction()
    {
        $ticket = $this->getTicket();
        if(is_null($ticket)){
            return;
        }
        $ticket->setClosed(0);
        $ticket->save();
        $this->_redirect('*/*/view', ['ticketId' => $ticket->getId()]);
    }

    //TODO: implement flash msg functionality
    protected function redirectFlash($message, $action='index')
    {
        $this->_redirect('*/*/'.$action);
    }

    /**
     * Returns ticket if customer has rights to access it,
     * otherwise returns null, gives flash message and sets redirect to indexAction
     * TODO: el ovo "malo" previse muljanja? :D
     */
    protected function getTicket(){
        $params = $this->getRequest()->getParams();
        $ticket = Mage::getModel('inchoo_tickentoo/ticket');

        //early returns, many 'error' checking
        if(!isset($params['ticketId'])) {
            $this->redirectFlash('No ticket id'); return;
        }
        $ticket->load($params['ticketId']);
        if(!$ticket->getId()){
            $this->redirectFlash('Ticket not found'); return;
        }
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        if($ticket->getCustomerId() != $customerId){
            $this->redirectFlash('You don\'t have permission to access that ticket'); return;
        }
        return $ticket;
    }

    //TODO: refactor this to helper
    protected function sendNotificationEmailToAdmin($ticket, $reply)
    {
        $tpl = Mage::getModel('core/email_template');
        $tpl->setDesignConfig([
                'area'  => 'frontend',
                'store' => Mage::app()->getStore()->getId()
            ]
        );

        $to = Mage::getStoreConfig('trans_email/ident_support/email');
        //$to = Mage::getStoreConfig('tickentoo_config/tickentoo_email_notifications/notification_email_address');
        $to_name = Mage::getStoreConfig('trans_email/ident_support/name');
        //$sender = Mage::getStoreConfig('trans_email/ident_general/name');
        $sender = [
            'name'  => $ticket->getCustomerName(),
            'email' => Mage::getStoreConfig('trans_email/ident_general/email')
        ];
        $vars = [
            'id'            => $ticket->getId(),
            'subject'       => $ticket->getSubject(),
            'message'       => $reply->getMessage(),
            'customerName'  => $sender['name']
        ];

        $selectedTemplate = Mage::getStoreConfig(
            'tickentoo_config/tickentoo_email_notifications/notification_email_template'
        );

        $tpl->sendTransactional($selectedTemplate, $sender, $to, $to_name, $vars);
    }
}