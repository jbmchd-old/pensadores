<?php

namespace Coluna\Model;

use Nucleo\Service\GenericTable;

class ColColunaTemChave extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('col_id', $options);
    }
}
?>