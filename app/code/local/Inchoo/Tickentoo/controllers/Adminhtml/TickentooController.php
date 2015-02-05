<?php

class Inchoo_Tickentoo_Adminhtml_TickentooController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $ticketsGridContainer = $this->getLayout()
            ->createBlock('inchoo_tickentoo/adminhtml_tickets');
        $this->loadLayout()
            ->_addContent($ticketsGridContainer);
        $this->renderLayout();
    }

    public function editAction()
    {
        $ticket = Mage::getModel('inchoo_tickentoo/ticket');
        $ticketId = $this->getRequest()->getParam('ticketId');
        $ticket->load($ticketId);
        if(!$ticket->getId()) {
            $this->_getSession()->addError('Ticket does not exist');
            return $this->_redirect('*/*/index');
        }

        $postData = $this->getRequest()->getPost();
        $this->saveTicket($ticket, $postData);

        Mage::register('current_ticket', $ticket);
        $ticketFormContainer = $this->getLayout()
            ->createBlock('inchoo_tickentoo/adminhtml_tickets_edit');
        $tabs = $this->getLayout()
            ->createBlock('inchoo_tickentoo/adminhtml_tickets_edit_tabs');
        $this->loadLayout()
            ->_addContent($ticketFormContainer)
            ->_addLeft($tabs)
            ->renderLayout();
    }

    /**
     * @param Inchoo_Tickentoo_Model_Ticket $ticket
     * @param $params request parameters
     */
    protected function saveTicket($ticket, $params)
    {
        if($params) {
            try {
                $ticket->addData($params);
                $ticket->save();
                if(array_key_exists('message', $params) and $params['message']) {
                    $adminId = Mage::getSingleton('admin/session')->getUser()->getId();
                    $reply = $ticket->makeReply($params['message'], 'admin', $adminId);
                    $reply->save();
                }
                $this->_getSession()->addSuccess($this->__('Ticket was saved'));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }

    public function massCloseAction()
    {
        $tickets_ids = $this->getRequest()->getParam('ticket_id');
        if(!is_array($tickets_ids))
        {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->__('Please select tickets!')
            );
        } else {
            try {
                $ticket = Mage::getModel('inchoo_tickentoo/ticket');
                $changed = 0;
                foreach($tickets_ids as $id) {
                    $ticket->load($id);
                    if(!$ticket->getClosed()) {
                        $ticket->setClosed(1);
                        $ticket->save();
                        $changed++;
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Closed '.$changed.' tickets')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}