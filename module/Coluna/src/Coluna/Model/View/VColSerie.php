<?php

namespace Coluna\Model\View;

use Nucleo\Service\GenericTable;

class VColSerie extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('ser_id', $options);
    }
}
?>