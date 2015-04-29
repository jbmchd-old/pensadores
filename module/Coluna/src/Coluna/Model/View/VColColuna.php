<?php

namespace Coluna\Model\View;

use Nucleo\Service\GenericTable;

class VColColuna extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('ctg_id', $options);
    }
}
?>