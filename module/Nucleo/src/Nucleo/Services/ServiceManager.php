<?php

namespace Nucleo\Services;

use ZeDb\DatabaseManager;

/**
 * Esta classe tem como objetivo realizar toda a comunicação entre os modulos e servicos entre os proprios modulos,
 * este gerenciador faz requisicoes de duas maneiras até o momento, usando os servicos do Zend Framework 2 ou usando Web Services.
 * Esta classe faz uso de duas classe externas, EcoGetServiceLocator() que e usada para fazer requisicoes do tipo Zend Framework 2,
 * @example Para fazer uma requisicao por um servico basta usar o metodo getEcoService('NOME_MODULO','NOME_SERVICO',1|2)
 * 1 e usado para fazer requisicoes usando os servicos do Zend Framework 2, 2 para utilizar Web Services.
 * @version 1.0
 */
class ServiceManager {

    /**
     * @var integer Modalidade do servico a ser utilizado 
     */
    private $modalidade;

    /**
     * @var string $modulo Nome do modulo que contem o servico
     */
    private $modulo;

    /**
     * @var string $servico Nome do servico
     */
    private $servico;

    /**
     * Nome da pasta que contem as classes
     * @var string
     */
    private $pasta;

    /**
     * @var \EntityManager $em Gerenciador de entidade que usado pelo servico do ZF2. OBRIGATORIO
     */
    private $em;

    /**
     * Este array contem as configurações de serviços dos módulos
     * @var array
     */
    private $config;
    
    private $service_namespace;

    /**
     * @var integer Constante que armazena o valor referente a modalide servico utilizando o ZF2
     */
    const ZEND_FRAMEWORK = 1;

    /**
     * @var integer Constante que armazena o valor referente a modalide servico utilizando o Web Services
     */
    const WEB_SERVICE = 2;

    /**
     * Seta os parametros da classe
     * @param EntityManager $em OBRIGATORIO
     * @param string $modulo
     * @param string $servico
     * @param number $modalidade
     * @param string $remetente
     */
    public function __construct(DatabaseManager $em, $config = array()) {
        $this->em = $em;
        $this->config = $config;
    }

    /**
     * Retorna tipo de modalidade
     * @return number
     */
    public function getModalidade() {
        return $this->modalidade;
    }

    /**
     * Define qual modalidade de servico a ser chamado
     * @param String $modalidade
     * @return \Ecossistema\GerService\GerServicoCliente
     */
    public function setModalidade($modalidade) {
        $this->modalidade = $modalidade;
        return $this;
    }

    /**
     * 
     * @return string $modulo
     */
    public function getModulo() {
        return $this->modulo;
    }

    /**
     * Nome do modulo que contem o servico
     * @param strinh $modulo
     * @return \Ecossistema\Service\GerServicoCliente
     */
    public function setModulo($modulo) {
        $this->modulo = $modulo;
        return $this;
    }

    /**
     * Nome do servico selecinado
     * @return string
     */
    public function getServico() {
        return $this->servico;
    }

    /**
     * Nome do servico a ser usado
     * @param string $servico
     * @return \Ecossistema\GerService\GerServicoCliente
     */
    public function setServico($servico) {
        $this->servico = $servico;
        return $this;
    }

    /**
     * Este metodo e usado para fazer uma chamada de servico e retornar uma instancia do mesmo
     * @param string $modulo
     * @param string $servico
     * @param integer $modalidade
     * @return Uma instancia do objeto do servico requisitado
     */
    public function getService($modulo, $servico, $modalidade = self::ZEND_FRAMEWORK) {
        
        $this->modalidade = $modalidade;
        $this->modulo = $modulo;
        $this->servico = $servico;
        $this->service_namespace = str_replace('/', '\\', $this->obtemNamespace() ) ;
        
//        $this->service_namespace = ;
        
        if ( ! class_exists($this->service_namespace) ){
            throw new \Exception("Erro ao carregar a classe $this->service_namespace, verique se a classe existe ou se os parametros foram passados corretamente!");
        }
        
        $instancia = null;
        if ($this->modalidade == self::ZEND_FRAMEWORK) {
            $this->configuraZeDb();
            $instancia = $this->em->get($this->service_namespace);
        } 
        return $instancia;
    }

    private function configuraZeDb(){
        $config = $this->em->getServiceLocator()->get('Configuration');
        $config = isset($config['zendexperts_zedb']) && (is_array($config['zendexperts_zedb']) || $config['zendexperts_zedb'] instanceof ArrayAccess)
        ? array_merge($config['zendexperts_zedb'], $this->geraArrayConfig($this->service_namespace))
        : array();
        $this->em->setConfig($config);
    }

    private function obtemNamespace(){

        $path = getcwd()."\\module\\$this->modulo\\src\\$this->modulo\\Entity"; 

        if(PHP_OS != 'WINNT'){
            $path = str_replace('\\', '/', $path);
        } 
        
        $iterator = new \RecursiveDirectoryIterator($path);
        $recursiveIterator = new \RecursiveIteratorIterator($iterator);

        $service = FALSE;
        foreach ( $recursiveIterator as $entry ) {
            if($entry->getFilename() == $this->servico.'.php'){
                $service = "$this->modulo\\Entity".substr($entry->getPathname(), strlen($path), -4);
                if(PHP_OS != 'WINNT'){
                    $service = str_replace('\\', '/', $service);
                }
                break;
            }
        }
        
        return $service;
    }
    
    private function geraArrayConfig($namespace){
        $model_class = str_replace('Entity', 'Model', $namespace);
        $namespace_exp = explode('\\', $namespace);
        $tabela = array_pop($namespace_exp);
        
        $table_name = strtolower($tabela[0]);
        for($i=1; $i<strlen($tabela);$i++){
            $char = $tabela[$i];
            $table_name .= ($char === strtoupper($char)) ? '_'.strtolower($char) : $char ;
        }
        
        return [
                'models' => [
                    $model_class => [
                        'tableName' => $table_name,
                        'entityClass' => $namespace,
                    ]
                ]
            ];
    }
    
}

?>