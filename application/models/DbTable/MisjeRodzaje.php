<?php

class Application_Model_DbTable_MisjeRodzaje extends Zend_Db_Table_Abstract
{

    protected $_name = 'misjerodzaje';
    protected $_dependentTables = array('Application_Model_DbTable_Misje');

}

