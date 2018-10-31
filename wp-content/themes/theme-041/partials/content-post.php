<li>
    <div class="actual_img">

        <?php 
            $mobile_detect = new Mobile_Detect();
            if( !$mobile_detect->isMobile() ) {
                the_post_thumbnail( 'project041-small' ); 
            } else {
                the_post_thumbnail( 'project041-list' );                 
            }
        ?> 
    </div>
    <div class="actual_details">
        <span><?php the_time( 'd-m-Y' ) ?> | <span>
            <?php if( function_exists( 'coauthors_links' ) ) { ?>
                <?php coauthors_links( ", ", ", " ); ?>
            <?php } else { ?>
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a>
            <?php } ?>
        </span>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_excerpt(); ?></p>
    </div>
</li>