<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>SIB</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="SIB - Sistema Integral de Beneficios Municipales" name="description">
    <meta name="keywords" content="SIB, Finanzas, Ayuntamiento, Centro, Gobierno">
    <meta content="tecnointel.mx" name="author">
    <link href="/images/web/favicon.png" rel="shortcut icon">
    <link href="/images/web/favicon.png" rel="apple-touch-icon">

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/responsive.css" rel="stylesheet">

    <link href="/css/style-t1.css" rel="stylesheet">

    <link href="/css/tables.css" rel="stylesheet">
    <link href="/css/forms.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/jquery-ui-1.10.3.custom.min.css" />
    <link rel="stylesheet" href="/assets/css/jquery-ui-1.10.3.full.min.css" />
    <link rel="stylesheet" href="/assets/css/font-awesome.css" />

    <!--[if IE 7]>
        <link rel="stylesheet" href="/assets/css/font-awesome-ie7.css" />
    <![endif]-->

    <link rel="stylesheet" href="/assets/css/ace-fonts.css" />

    <!--ace styles-->

    <link rel="stylesheet" href="/assets/css/ace.css" />
    <link rel="stylesheet" href="/assets/css/ace-responsive.css" />
    <link rel="stylesheet" href="/assets/css/ace-skins.css" />
    <link rel="stylesheet" href="/assets/css/datepicker.css" />
    <link rel="stylesheet" href="/assets/css/colorpicker.css" />
    <link rel="stylesheet" href="/assets/css/jquery.gritter.css" />
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="/assets/css/ace-ie.css" />
    <![endif]-->

    <link href="/css/docs.css" rel="stylesheet">
    <link href="/css/sg-01.css" rel="stylesheet">

    <script src="/assets/js/ace-extra.min.js"></script>
    

<style type="text/css">

body{background: transparent url("/img/blueprint.png") top left; }

</style>

<script type="text/javascript">


    
</script>

</head>
<body>

    <?php include("php/01/templates/navbar-top.php"); ?>
    
    <section class="section-portfolio" id="section-portfolio" >
 
        <div class="main-container container-fluid" style="background-color: transparent;">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>


            <?php include("php/01/templates/sidebar-left.php"); ?>

            <div class="main-content">

                <div class="breadcrumbs transparent" id="breadcrumbs">
                    <ul class="breadcrumb" id="breadcrumb">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a id="href0" href="/dashboard/">Inicio</a>
                        </li>
                    </ul><!--.breadcrumb--> 
                    <span id="barInfoL0" class=""></span>                   
                    <span id="barInfoR0" class="pull-right"></span>                   

                </div>

                <div class="page-content">
                    <div class="row-fluid">
                        <div class="span12">
                            <!--PAGE CONTENT BEGINS-->
                            <div class="container inw100p " id="contentMain" ></div>
                            <div class="container inw100p " id="contentProfile"></div>
                            <div class="container inw100p " id="contentLevel3"></div>

                            <!--PAGE CONTENT ENDS-->
                        </div><!--/.span-->
                    </div><!--/.row-fluid-->
                </div><!--/.page-content-->

            </div><!--/.main-content-->
        </div><!--/.main-container-->



    </section>


    <!-- .................................... $post-footer .................................... -->
    <?php include("php/01/templates/footer.php"); ?>


    <div class="modal fade bs-example-modal-lg" tabindex="-1" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="contentModal1">
                <img src="/img/loading.gif" width="32" height="32" alt=""/> Cargando...
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->  

    <div id="divUploadImage" class="modal fade " style = "height:600px !important; width: 600px !important;">
    </div><!-- /.modal -->

    <script type="text/javascript" src="/js/01/accounting.js"></script>

    <!-- .................................... $scripts .................................... -->
    <script src="/js/libs/jquery.min.js"></script>


    <script src="/bootstrap/js/bootstrap.min.js"></script>

    <!--basic scripts-->

    <!--[if !IE]>-->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
    </script>

    <!--<![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->

    <script type="text/javascript">
        if("ontouchend" in document) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>

    <script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="/assets/js/jquery-ui-1.10.3.full.min.js"></script><!-- Nuevo -->
    <script src="/assets/js/jquery.gritter.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"> </script>
    <script src="/assets/js/jquery.dataTables.bootstrap.js"> </script>

    <script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/js/bootbox.min.js"></script>
    <script src="/assets/js/jquery.easy-pie-chart.min.js"></script>
    <!-- <script src="/assets/js/jquery.gritter.min.js"></script> -->
    <script src="/assets/js/spin.min.js"></script>
    
    <script src="/assets/js/ace-elements.min.js"></script>
    <script src="/assets/js/ace.min.js"></script>

    <script src="/assets/js/date-time/bootstrap-datepicker.min.js"></script>
    <script src="/assets/js/bootstrap-colorpicker.min.js"></script>
    <script src="/assets/js/jquery.maskedinput.min.js"></script>


    <script  src="/js/01/base.js"> </script>
    
    <script typy="text/javascript">
        document.write("<script src='"+obj.getValue(4)+"/socket.io/socket.io.js'>"+"<"+"/script>");
    </script>    


    <script  src="/js/init.js"> </script>

<script>

$(".dropdown-toggle").click(function () {
$(".nav-collapse").css('height', 'auto')
});

</script>
</body>
</html>