<!DOCTYPE html>
<html lang="es">
<head>

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94376-25"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', 'UA-94376-25');
</script>

<!-- End Google Tag Manager -->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">

<link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon">

<title><?php echo wp_title( '' ); ?></title>

<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Mukta:200,300,400,500,600,700,800" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.carousel.min.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.theme.min.css" rel="stylesheet">

<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/tooltipster.bundle.min.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/tooltipster-sideTip-shadow.min.css" rel="stylesheet">

<link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/addon.min.css?v=<?php echo Project041_Definitions::$scripts_version; ?>" rel="stylesheet">

 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php wp_head(); ?>

<meta name="google-site-verification" content="QD42wTQrCV4hWcev9YuFj-yGKPIgvCmpwOUNkaB6GLU" />

</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N96XFSN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



      <?php 
      $mobile_detect = new Mobile_Detect();
      if( !$mobile_detect->isMobile() ) {
      ?>
      <div class="addbanner">

        <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
        <script>
          var googletag = googletag || {};
          googletag.cmd = googletag.cmd || [];
        </script>

        <script>
          googletag.cmd.push(function() {
            googletag.defineSlot('/1006594/728x90_project041', [728, 90], 'div-gpt-ad-1526282596829-0').addService(googletag.pubads());
            googletag.enableServices();
          });
        </script>
        <!-- /1006594/728x90_project041 -->
        <div id='div-gpt-ad-1526282596829-0' style='auto'>
        <script>
        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1526282596829-0'); });
        </script>
        </div>

        <?php //if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner cabecera' ) ) : ?>
        <?php //endif; ?>
        
      </div>
      <?php } ?>
      

<section class="header">
  <div class="top_header clearfix">
    
    <div class="container" style="position:relative">

      <div class="banners-wrapper">
      <div class="banner-160x600 banner-160x600-left">
      <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner 160x600 izquierda' ) ) : ?>
        <?php endif; ?>
      </div>

      <div class="banner-160x600 banner-160x600-right">
        <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner 160x600 derecha' ) ) : ?>
        <?php endif; ?>
      </div>  
      </div>
    </div>  



  </div>

  <div class="main_header clearfix">
    <div class="container">
      <nav class="navbar navbar-default main_nav">            
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> 
          <div class="toggle_normal"></div>
          <div class="toggle_open"></div>
           </button>
          <a class="navbar-brand" href="<?php echo home_url(); ?>" title="Project041"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="Project041"/></a> 
         </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-left">
            
            <?php wp_nav_menu( array( 'theme_location' => 'headermenu', container => '', 'items_wrap' => '%3$s' ) ); ?>
            
          </ul>
          
          <div class="searchdiv">
      
            <form class="search-form" role="search" metod="GET" action="<?php echo home_url(); ?>">
              <div class="form-group pull-right" id="search">
                  <input type="text"  class="form-control" placeholder="<?php _e( 'Introduce tu búsqueda', 'project-041' ); ?>" id="s" name="s" />
                  <input type="hidden" class="form-control form-control-submit" name="post_type" value="post" />
                  <input type="submit" value="<?php _e( 'buscar', 'project-041' ); ?>" name="">
                  <span class="search-label"><i class="fa fa-search" aria-hidden="true"></i></span>
              </div>
            </form>
     
          </div>

        </div>
              
      </nav>
    </div>
  </div>  

<!--
  <div class="main_header clearfix">
    <div class="container">
      <nav class="navbar navbar-default main_nav">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controlclass="searchbar" id="searchbtn" href="javascript:void(0);"><i class="fa fa-search" aria-hidden="true"></i></a>
               <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
          <a class="navbar-brand" href="<?php echo home_url(); ?>" title="Project041"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="Project041" /></a> </div>
        <div id="navbar" class="navbar-collapse collapse">

        <ul class="nav navbar-nav navbar-right">
          <?php wp_nav_menu( array( 'theme_location' => 'headermenu', container => '', 'items_wrap' => '%3$s' ) ); ?>               

          <li class="search-li"><a class="searchbar" id="searchbtn" href="#"><i class="fa fa-search header-search" aria-hidden="true"></i></a>
              <div class="searcharea">
                <form metod="GET" action="<?php echo home_url(); ?>">
                  <input type="text" placeholder="<?php _e( 'Introduce tu búsqueda', 'project-041' ); ?>" id="s" name="s" />
                  <input type="hidden" name="post_type" value="post" />
                  <input type="submit" value="<?php _e( 'buscar', 'project-041' ); ?>" name="">
                </form>
              </div>
            </li>
            
        </ul>
  
        </div>
      </nav>
    </div>
  </div>
  -->
  
</section>

<section class="bodycontent">
