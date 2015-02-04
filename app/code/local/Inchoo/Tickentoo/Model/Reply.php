<?php

//TODO: ->setAuthor(Customer|Admin $user)  //sets author type and id, exception on invalid data
//      that way handling of multiple author types is tied only to this model, outside world does not know about it
class Inchoo_Tickentoo_Model_Reply extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('inchoo_tickentoo/reply');
    }


    //TODO: refactor this into trait
    protected function _beforeSave()
    {
        if(!$this->getId()){
            $this->setCreatedAt(Varien_Date::now());
        }
        parent::_beforeSave();
    }

    public function getAuthorName()
    {
        if($this->getAuthorType() == 'customer'){
            $model = 'customer/customer';
        } elseif ($this->getAuthorType() == 'admin') {
            $model = 'admin/user';
        }
        $user = Mage::getModel($model)
            ->load($this->getAuthorId());
        if(!$user->getId()){
            //TODO: throw exception, invalid data, hard to enforce foreign key
            return null;
        }
        return $user->getName();
    }
}