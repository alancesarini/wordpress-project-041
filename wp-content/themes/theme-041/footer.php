
<div class="container container-banner-footer">
    <div class="addbanner pull-left">
      <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Banner footer' ) ) : ?>
      <?php endif; ?>
    </div>   
</div>
</section>

<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-sm-5">
        <h1><?php _e( 'Navegación', 'project-041' ); ?></h1>

        <?php wp_nav_menu( array( 'theme_location' => 'footermenu', container => '', 'menu_class' => 'ft_menu' ) ); ?>               

      </div>
      <div class="col-sm-3">
        <h1><?php _e( 'Info de contacto', 'project-041' ); ?></h1>
        <p><span>e:</span> <a href="mailto:hello@project041.com">hello@project041.com</a></p>
        <p><span>t:</span> 999 888 777</p>
      </div>
      <div class="col-sm-4">
        <h1><?php _e( 'Síguenos', 'project-041' ); ?></h1>

        <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'Iconos sociales en pie' ) ) : ?>
        <?php endif; ?>

      </div>
    </div>
  </div>
</footer>

<div class="event-popup">
  <div id="popup-content" class="content">
    <h4></h4>
    <p class="event-text"></p>
  </div>
</div>



<?php wp_footer(); ?>

</body>
</html>
