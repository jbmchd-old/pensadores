function main() {
    $(function() {
        /*
         * DEFAULTS
         */
        $('[data-toggle="tooltip"], [data-tooltip]').tooltip();
        $('[data-toggle="popover"], [data-popover]').popover();
        
        $('div.popover').is(':visible')
        
        // AJAX Defalts
        $.ajaxSetup({
            dataType: 'json',
            type: 'POST',
            beforeSend: function ()     { var url = this.url.split('/').join('_'); $().loading(true, url); },
            complete:   function ()     { var url = this.url.split('/').join('_'); $().loading(false, url); },
            error:      function (error){ console.log(error); },
        });
        
        /*
         * FORMATAÇÕES DE TABELAS
         */
        $('.table').formatacao().tabela();

        /*
         * RELACIONADOS AOS FORMULARIOS
         */
        campos_texto = new Array('textarea, input[type=text],input[type=password]');

        /*
         * Acoes gerais
         */
        $(campos_texto.join()).on('keyup', function() {
            $form().normalizaCampoComErro(this);
        });

        $('select').on('change', function() {
            $form().normalizaCampoComErro(this);
        });

        /*
         * Acoes especificas
         */
        $form().atrelaEventosPorAtributos( $('form').find( campos_texto.join() ) );

        /*
         * Acoes gerais
         */
        $('form').on('submit', function() {
            return $form().validaSubmissaoFormulario(this);
        });
        

        
    });
}

main();



