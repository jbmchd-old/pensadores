<?php

namespace Coluna\Model\View;

use Nucleo\Service\GenericTable;

class VColUltimasPostadas extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('col_id', $options);
    }
}
?>