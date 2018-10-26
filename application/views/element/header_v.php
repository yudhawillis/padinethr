<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="">

<?php
echo link_tag('assets/templates/admin_temp/vendor/bootstrap/css/bootstrap.min.css');
echo link_tag('assets/templates/admin_temp/vendor/metisMenu/metisMenu.min.css');
echo link_tag('assets/templates/admin_temp/dist/css/sb-admin-2.css');
echo link_tag('assets/css/styles.css');
//echo link_tag('assets/templates/admin_temp/vendor/font-awesome/css/font-awesome.min.css');

//echo link_tag('assets/css/datepicker3.css');
?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="<?php echo assets_url(); ?>plugin/tinymce/js/tinymce/tinymce.min.js"></script>

<script>

    tinymce.init({
        relative_urls:false,
//        external_filemanager_path:"/assets/plugin/tinymce/js/tinymce/plugins/filemanager/",
//        filemanager_title:"filemanager" ,
//        external_plugins: { "filemanager" : "assets/plugin/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"}
        file_browser_callback : 'myFileBrowser',
//        subfolder:"",
        //selector: 'textarea',
        mode : "specific_textareas",
        editor_selector : /(mceEditor|mceRichText)/,
        height: 250,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools code code'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],

        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ],
        codesample_dialog_width: 100,
        codesample_dialog_height: 100

    });

</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
<link rel="stylesheet" href="<?php echo assets_url(); ?>plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/themes/github.css">




<!--<link rel="stylesheet" type="text/css" href="--><?php //echo assets_url(); ?><!--plugin/Tokenize-2.5.2/jquery.tokenize.css" />-->
<!---->
<!--<link href="--><?php //echo assets_url(); ?><!--plugin/tag-it/css/jquery.tagit.css" rel="stylesheet" type="text/css">-->
<!--<link href="--><?php //echo assets_url(); ?><!--plugin/tag-it/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">-->