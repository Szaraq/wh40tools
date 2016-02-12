<?php

class Application_Model_DbTable_Misje extends Zend_Db_Table_Abstract
{

    protected $_name = 'Misje';
    protected $_referenceMap = array(
        'Rodzaj' => array(
            'columns' => array('rodzaj'),
            'refTableClass' => 'Application_Model_DbTable_MisjeRodzaje',
            'refTableColumns' => array('id')
        )
    );

}

