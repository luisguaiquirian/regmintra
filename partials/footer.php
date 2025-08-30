    </div>
  </div>
  </section>

<!-- Vendor -->
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery/jquery.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/bootstrap/js/bootstrap.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/nanoscroller/nanoscroller.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/magnific-popup/magnific-popup.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-placeholder/jquery.placeholder.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/gauge/gauge.js' ?>"></script>

        <!-- Specific Page Vendor -->
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/select2/select2.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-datatables/media/js/jquery.dataTables.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-datatables-bs3/assets/js/datatables.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-appear/jquery.appear.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-easypiechart/jquery.easypiechart.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/flot-tooltip/jquery.flot.tooltip.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/flot/jquery.flot.pie.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/flot/jquery.flot.categories.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/flot/jquery.flot.resize.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/jquery-sparkline/jquery.sparkline.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/raphael/raphael.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/morris/morris.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/snap-svg/snap.svg.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/liquid-meter/liquid.meter.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/vendor/flot/jquery.flot.js' ?>"></script>

        
        <!-- Theme Base, Components and Settings -->
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/theme.js'?>"></script>
        
        <!-- Theme Custom -->
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/theme.custom.js'?>"></script>
        
        <!-- Theme Initialization Files -->
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/theme.init.js'?>"></script>

        <!-- Examples -->
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/tables/examples.datatables.default.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/ui-elementselements/examples.charts.js' ?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/javascripts/ui-elements/examples.modals.js' ?>"></script>
    
        <script src="<?= $_SESSION['base_url1'].'assets/js/toastr.js'?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/js/alertify.min.js'?>"></script>
        <script src="<?= $_SESSION['base_url1'].'assets/js/multiselect/js/jquery.multi-select.js'?>"></script>
        
  </body>
</html>

<script>
    function mayus(e) {
        e.value = e.value.toUpperCase();
    }

    $(function(){
        $('[data-tool="tooltip"]').tooltip()

        let type = "<?= isset($_SESSION['flash']) ? $_SESSION['flash'] : null ?>";

        if(type)
        {
            switch(type)
            {
                case '1':
                    toastr.success('Operación realizada con éxito','Éxito!')
                break;

                case '2':
                    toastr.error('Ha ocurrido un error al ejecutar la operación','Error!')
                
                case '3':
                    toastr.error('El archivo no es una imagen','Error!')
                break;
                 case '4':
                    toastr.error('el tamaño máximo permitido es un 1MB','Error!')
                break;
              case '5':
                    toastr.error('La unidad ya se encuentra asignada a otro conductor','Error!')
                break;
              case '6':
                    toastr.error('No puede eliminar este afiliado porque tiene unidades registradas','Error!')
                break;
              case '7':
                    toastr.error('No se puede eliminar esta La línea porque posee Socios registrados','Error!')
                break;
              case '8':
                    toastr.error('Rellene todos los campos','Error!')
                break;
              case '9':
                    toastr.error('Esta ruta tiene unidades de transporte asociadas','Error!')
                break;
            }

            <? unset($_SESSION['flash']); ?>
        }

        let type_message = "<?= isset($_SESSION['type_flash']) ? $_SESSION['type_flash'] : null ?>";
        
        if(type_message){
            var message = "<?= isset($_SESSION['message']) ? $_SESSION['message'] : null ?>";

            switch (type_message) {
                case "success":        
                    toastr.success(message,'Éxito!')
                break;

                case "warning":
                    toastr.warning(message,'Alerta!')
                break;

                case "danger":
                    toastr.error(message,'Error!')
                break;
            }

            <? unset($_SESSION['type_flash']); ?>
            <? unset($_SESSION['message']); ?>
        }
            

        $('.remover_helper').click(function(){
            let agree = confirm('¿Está seguro de querer eliminar este registro?')

             if(!agree)
            {
                return false
            }
        })        
        $('.update_helper').click(function(){
            let agree = confirm('¿Está seguro Aprobar a este Vocero?')

            if(!agree)
            {
                return false
            }
        })
    })

function number_format(number, decimals, dec_point, thousands_point) {

    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split(',').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
}

	/*
	Gauge: Basic
	*/
	(function() {
		var target = $('#gaugeBasic'),
			opts = $.extend(true, {}, {
				lines: 12, // The number of lines to draw
				angle: 0.12, // The length of each line
				lineWidth: 0.5, // The line thickness
				pointer: {
					length: 0.7, // The radius of the inner circle
					strokeWidth: 0.05, // The rotation offset
					color: '#444' // Fill color
				},
				limitMax: 'true', // If true, the pointer will not go past the end of the gauge
				colorStart: 'RED', // Colors
				colorStop: 'RED', // just experiment with them
				strokeColor: '#F1F1F1', // to see which ones work best for you
				generateGradient: true
			}, target.data('plugin-options'));

			var gauge = new Gauge(target.get(0)).setOptions(opts);

		gauge.maxValue = opts.maxValue; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(opts.value); // set actual value
		gauge.setTextField(document.getElementById("gaugeBasicTextfield"));
	})();
 	/*
	Gauge: Basic
	*/
	(function() {
		var target = $('#gaugeBasic2'),
			opts = $.extend(true, {}, {
				lines: 12, // The number of lines to draw
				angle: 0.12, // The length of each line
				lineWidth: 0.5, // The line thickness
				pointer: {
					length: 0.7, // The radius of the inner circle
					strokeWidth: 0.05, // The rotation offset
					color: '#444' // Fill color
				},
				limitMax: 'true', // If true, the pointer will not go past the end of the gauge
				colorStart: 'RED', // Colors
				colorStop: 'RED', // just experiment with them
				strokeColor: '#F1F1F1', // to see which ones work best for you
				generateGradient: true
			}, target.data('plugin-options'));

			var gauge = new Gauge(target.get(0)).setOptions(opts);

		gauge.maxValue = opts.maxValue; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(opts.value); // set actual value
		gauge.setTextField(document.getElementById("gaugeBasicTextfield2"));
	})();

function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8 || tecla==9 || tecla==0){
        return true;
    }    

        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function valida1(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8 || tecla==100 || tecla==9 || tecla==0){
        return true;
    }    

        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

</script>