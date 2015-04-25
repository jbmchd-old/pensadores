<?php

namespace Coluna\Model;

use Nucleo\Services\GenericTable;

class ColChave extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('chv_id', $options);
    }
}
?>