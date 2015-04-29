<?php

namespace Coluna\Model;

use Nucleo\Service\GenericTable;

class ColChave extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('chv_id', $options);
    }
}
?>