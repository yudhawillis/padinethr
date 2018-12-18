<script src="<?php echo assets_url(); ?>templates/admin_temp/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo assets_url(); ?>templates/admin_temp/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo assets_url(); ?>templates/admin_temp/vendor/metisMenu/metisMenu.min.js"></script><!-- Metis Menu Plugin JavaScript -->
<script src="<?php echo assets_url(); ?>templates/admin_temp/dist/js/sb-admin-2.js"></script><!-- Custom Theme JavaScript -->

<script src="<?php echo assets_url(); ?>js/bootstrap-datepicker.js"></script>
<!--<script type="text/javascript" src="--><?php //echo assets_url(); ?><!--plugin/Tokenize-2.5.2/jquery.tokenize.js"></script>-->
<script src="<?php echo assets_url(); ?>plugin/bootstrap-filestyle-1.2.1/src/bootstrap-filestyle.min.js"></script>
<!--<script src="--><?php //echo assets_url(); ?><!--plugin/tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="<?php echo assets_url(); ?>plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo assets_url(); ?>plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js"></script>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
<!--<link rel="stylesheet" href="/resources/demos/style.css">-->
<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
<!--<script src="--><?php //echo assets_url(); ?><!--plugin/datepicker/js/bootstrap-datepicker.js"></script>-->


<script>
	// $( "#datepicker" ).datepicker({ minDate: -20, maxDate: "+1M +10D" });

    $(document).ready(function(){
		// $.fn.datepicker.defaults.format = "yyyy-mm-dd";
		// $('.datepicker').datepicker({
		// 	startDate: '-3d'
		// });
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			startDate: '0d'
		});

        $('.datepicker-search').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
