<?php

namespace Coluna\Model;

use Nucleo\Services\GenericTable;

class ColColuna extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('col_id', $options);
    }
}
?>