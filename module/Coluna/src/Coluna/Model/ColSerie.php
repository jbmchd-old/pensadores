<?php

namespace Coluna\Model;

use Nucleo\Service\GenericTable;

class ColSerie extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('ser_id', $options);
    }
}
?>