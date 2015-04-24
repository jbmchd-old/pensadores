var $form = function (){
    campos_texto = new Array('textarea, input[type=text],input[type=password]');
    
    /**
     * Funcoes de formularios
     */
    
    this.atrelaEventosPorAtributos = function (campos){
        $(campos).each(function (){
            campo = this;
            atributos = campo.attributes;
            $(atributos).each(function() {
                /**
                 * Atrela o filter_input aos campos que possuam o atributo 'filterInput'
                 */
                name = this.name;
                value = this.value;
                if (name === 'data-filterinput') {
                    if(!value){
                        value = '[A-Za-zÀ-ú0-9 ]';
                    }
                    $(campo).filter_input({regex: value});
                }
                
                if(name === 'data-mask' && value){
                    $(campo).mask(value);
                }
            });
        });
    };
    
    this.normalizaCampoComErro = function (campo){
        $(campo).each(function (){
            if (!estaVazio(this)) {
                $(campo).css({
                    border: ''
                });
                $form().removeAvisoCampoObrigatorio(this);
            }
        });
    };
    
    this.removeAvisoCampoObrigatorio = function(campo) {
        if(campo){
            
            aviso = $(campo).parents('.input-group').next('ul[name=aviso_campo_obrigatorio]');
            if (aviso) {
                $(aviso).remove();
            }
        }
    };

    this.estaVazio = function(campo) {
        if(campo){
            valor = campo.value.replace(/ /g, '');
            valor = valor.toLowerCase();
            if (valor === '' || valor === 'null') {
                return true;
            } else {
                return false;
            } 
        }
    };

    this.campoObrigatorio = function(campo) {
        if(campo){
            
            tag = campo.tagName;
            erro = 0;
            
            if (estaVazio(campo)) {
                msg = 'Este campo é obrigatório.';
                erro = 1;
            } else if(tag === 'SELECT' && parseInt($(campo).val()) === 0){
                erro = 1;
                msg = 'Este campo é obrigatório.';
            } else if(tag !== 'SELECT' && campo.value.length === 1) {
                msg = 'Poucos caracteres.';
                erro = 1;
            }

            if(erro){
                removeAvisoCampoObrigatorio(campo);
                $(campo)
                        .css('border', '1px solid rgb(219, 125, 125)')
                        .parents('.input-group')
                        .after('<ul name=aviso_campo_obrigatorio class="text-muted" style="margin-bottom:-10px; padding:0 0 0 20px"><li><i>' + msg + '</i></li></ul>');

                return false;
            }
            return true;
        }
    };
    
    this.validaSubmissaoFormulario = function (form){
        ok = true;
        tudo_ok = true;

        $(form).find(campos_texto.join() + ', select').each(function() {
            if (typeof $(this).attr('data-obrigatorio') === 'string') {
                ok = campoObrigatorio(this);
                if(!ok){
                    tudo_ok=false;
                }
            }
        });
        return tudo_ok;
    };
    
    this.todosTratamentos = function (form) {

        $(campos_texto.join()).on('keyup', function() {
            normalizaCampoComErro(this);
        });

        $('select').on('change', function() {
            normalizaCampoComErro(this);
        });

        atrelaEventosPorAtributos( $(form).find( campos_texto.join() ) );

        $('form').on('submit', function() {
            return validaSubmissaoFormulario(this);
        });
    };
    
    return this;
};
