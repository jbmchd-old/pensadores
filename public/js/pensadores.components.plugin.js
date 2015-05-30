/**
 * @author Joabe Machado de Abreu <jb.machado42@gmail.com.br>
 * @description 
 * @version 1.0 31/03/2015
 */
(function ($) {
    /**
     * 
     * @param Você pode passar a função que vai executar quando o usuario clicar em sim
     * @param ou passar um objeto no seguinte estilo
     *  $e("#teste").confirm({
     yes:function(){
     alert('sim');
     },
     no:function(){
     alert("cancelou");
     },
     text:"Mensagem do alerta",
     titulo : "Título do alert",
     noLabel : "Texto do botao noLabel",
     yesLabel : "Texto do botap yesLabel",
     });
     * @returns {undefined}
     */

    $.fn.confirm = function (options, yes) {
        
        seletor = (this.selector)?this[0]:false;

        var settings = $.extend({
            // These are the defaults.
            titulo: '',
            text: 'Confirma a operação?',
            noLabel: 'Não',
            yesLabel: 'Sim',
            preAcao: '',
            yes: function () {},
            no: function () {}
        }, options);

        if (typeof options === "function") {
            settings.yes = options;
        } else if (typeof options === 'string') {
            settings.text = options;
            if (typeof yes === 'function') {
                settings.yes = yes;
            }
        }

        var modal = '\
                <div class="modal fade" id="modal_confirmar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
                    <div class="modal-dialog modal-sm" >\
                        <div class="modal-content">\
                            <div class="modal-header" >\
                                <i class="fa fa-question-circle"></i>\
                                <span class="modal-title" style="font-size:15px; font-weight:600">' + settings.titulo + '</span>\
                            </div>\
                            <div class="modal-body" >\
                                <span style="color:black; font-size:13px;">' + settings.text + '</span> \
                            </div>\
                            <div class="modal-footer" style="padding:10px; text-align:center">\
                              <button type="button" id="nao_confirma" class="btn btn-primary" data-dismiss="modal" ><i class="fa fa-times" ></i> ' + settings.noLabel + '</button>\
                              <button type="button" id="sim_confirma" class="btn btn-danger"><i class="fa fa-check" ></i> ' + settings.yesLabel + '</button>\
                            </div>\
                        </div>\
                    </div>\
                </div>\ ';

        $("body").append(modal);

        $("#sim_confirma").click(function () {
            
            if (typeof settings.yes === "function") {
                var retorno = settings.yes(seletor);
            } 
            
            if(retorno){
                if ($(seletor).prop('tagName') === 'A' && $(seletor).attr('href') !== '#' && $(seletor).attr('href') !== 'undefined') {
                    location.href = $(seletor).attr('href');
                }
            }
            
            $("#modal_confirmar").modal('hide');
            
        });

        $("#nao_confirma").click(function () {
            if (typeof settings.no === "function") {
                settings.no(seletor);
                $("#modal_confirmar").modal('hide');
                return false;
            } 
        });

        if (typeof settings.preAcao === 'function') {
//            var retorno = settings.preAcao(seletor);
            if ( settings.preAcao(seletor) ) {
                //abre o confirm
                $("#modal_confirmar").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        } else {
            //abre o confirm
            $("#modal_confirmar").modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        
    };
    
    $.fn.alert = function (text) {
        
        if(typeof text === 'undefined' || text.trim() === ''){
            text = '';
        };
        
        var modal = '\
                <div class="modal fade" id="modal_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
                    <div class="modal-dialog modal-sm" >\
                        <div class="modal-content">\
                            <div class="modal-header" >\
                                <i class="fa fa-exclamation-circle"></i> Atenção!\
                            </div>\
                            <div class="modal-body" >\
                                <span style="color:black; font-size:13px;">' + text + '</span> \
                            </div>\
                            <div class="modal-footer" style="padding:10px; text-align:center">\
                              <button type="button" id="ok" class="btn btn-primary" data-dismiss="modal" ><i class="fa fa-check" ></i> Ok</button>\
                            </div>\
                        </div>\
                    </div>\
                </div>\ ';

        $("body").append(modal);

        //abre o alert
        $("#modal_alert").modal({
            backdrop: 'static',
            keyboard: false
        });
    };

    $.fn.loading = function (option, diferenciador) {
        
        diferenciador = (typeof diferenciador === 'undefined')?'':'_'+diferenciador;
        
        var param_tipo = typeof option;
        var titulo = 'Aguarde...';
        var abrir = true;
        
        
        if(param_tipo === 'string' && option.trim()!==''){
            titulo = option; 
        } else if(param_tipo === 'boolean' || param_tipo === 'number'){
            abrir = Boolean(option);
        }
        
        var id_modal = 'modal_loading'+diferenciador;
        if(abrir){
            if( ! $('#'+id_modal).length ){
                var modal = '\
                        <div class="modal fade" id="'+id_modal+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
                            <div class="modal-dialog modal-sm" >\
                                <div class="modal-content">\
                                    <div class="modal-header" >\
                                        <i class="fa fa-info-circle"></i>\
                                        <span class="modal-title" style="font-size:15px; font-weight:600">' + titulo + '</span>\
                                    </div>\
                                    <div class="modal-body text-center" >\
                                        <span style="color:black; font-size:13px;">\
                                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>\
                                        </span> \
                                    </div>\
                                    <div class="modal-footer" style="padding:10px; text-align:center"></div>\
                                </div>\
                            </div>\
                        </div>\ ';

                $("body").append(modal);
            }
            //abre o loading
            $('#'+id_modal).modal({
                backdrop: 'static',
                keyboard: false
            });
        } else {
            //fecha o loading
            $('#'+id_modal).modal('hide');
        }
    };

    $.fn.showMessageTarge = function (options){
        destino = this.selector;
        seletor = (this.selector)?this[0]:false;
        if(typeof seletor === 'undefined'){
            console.log('Elemento DOM inexistente');
            return false;
        }
        
        if(typeof options!=='object' || options.message.trim()=='' || options.type.trim()=='' ){
            console.log('Erro com alguns dos parametros verifique');
            return false;
        }
        
        var settings = $.extend({
            message:'',
            type:''
        }, options);
        
        $(destino)
                .html(settings.message)
                .removeClass()
                .addClass('col-lg-30 label label-'+settings.type)
                .slideDown("slow", function(){
                    setTimeout(function(){ 
                       $(destino).hide();
                    }, 5000);
                });
    }

}(jQuery));