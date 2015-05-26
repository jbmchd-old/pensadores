<?php

namespace Acesso\Model;

use Nucleo\Service\GenericTable;

class AcsUsuario extends GenericTable {
	
    public function __construct($options = null) {
        parent::__construct('usr_id', $options);
    }
}
?>