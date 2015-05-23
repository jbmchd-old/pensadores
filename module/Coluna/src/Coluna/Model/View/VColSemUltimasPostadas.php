<?php

namespace Coluna\Model\View;

use Nucleo\Service\GenericTable;

class VColSemUltimasPostadas extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('col_id', $options);
    }
}
?>