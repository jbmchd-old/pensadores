<?php

namespace Coluna\Model;

use Nucleo\Service\GenericTable;

class ColCategoria extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('ctg_id', $options);
    }
}
?>