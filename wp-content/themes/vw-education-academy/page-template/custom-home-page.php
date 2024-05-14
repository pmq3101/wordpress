<?php
/**
 * Template Name: Custom Home Page
 */

get_header(); ?>

<main id="maincontent" role="main">
  <?php do_action( 'vw_education_academy_before_slider' ); ?>

  <?php if( get_theme_mod( 'vw_education_academy_slider_hide_show', false) == 1 || get_theme_mod( 'vw_education_academy_resp_slider_hide_show', true) == 1) { ?>

  <section id="slider">
    <?php if(get_theme_mod('vw_education_academy_slider_type', 'Default slider') == 'Default slider' ){ ?>
      <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="<?php echo esc_attr(get_theme_mod( 'vw_education_academy_slider_speed',4000)) ?>">
        <?php $vw_education_academy_slider_pages = array();
          for ( $count = 1; $count <= 3; $count++ ) {
            $mod = intval( get_theme_mod( 'vw_education_academy_slider_page' . $count ));
            if ( 'page-none-selected' != $mod ) {
              $vw_education_academy_slider_pages[] = $mod;
            }
          }
          if( !empty($vw_education_academy_slider_pages) ) :
            $args = array(
              'post_type' => 'page',
              'post__in' => $vw_education_academy_slider_pages,
              'orderby' => 'post__in'
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
              $i = 1;
        ?>     
        <div class="carousel-inner" role="listbox">
          <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
              <?php if(has_post_thumbnail()){
                the_post_thumbnail();
              } else{?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/block-patterns/images/banner.png" alt="" />
              <?php } ?>
              <div class="carousel-caption">
                <div class="inner_carousel">
                  <h1 class="wow slideInRight delay-1000" data-wow-duration="2s"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                  <p class=" wow slideInLeft delay-1000" data-wow-duration="2s"><?php $vw_education_academy_excerpt = get_the_excerpt(); echo esc_html( vw_education_academy_string_limit_words( $vw_education_academy_excerpt, esc_attr(get_theme_mod('vw_education_academy_slider_excerpt_number','30')))); ?></p>
                  <?php if( get_theme_mod('vw_education_academy_slider_button_text','Read More') != ''){ ?>
                    <div class ="more-btn wow slideInRight delay-1000" data-wow-duration="2s">
                        <a class="view-more" href="<?php echo esc_url(get_theme_mod('vw_education_academy_contact_link','')); ?>"><span><?php echo esc_html(get_theme_mod('vw_education_academy_slider_button_text',__('Read More','vw-education-academy')));?></span>
                        <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_slider_button_icon','fas fa-arrow-right')); ?>"></i>
                        <span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_education_academy_slider_button_text',__('Read More','vw-education-academy')));?></span></a>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          <?php $i++; endwhile; 
          wp_reset_postdata();?>
        </div>
        <?php else : ?>
            <div class="no-postfound"></div>
        <?php endif;
        endif;?>
        <a class="carousel-control-prev" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev" role="button">
          <span class="carousel-control-prev-icon w-auto h-auto" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
          <span class="screen-reader-text"><?php esc_html_e( 'Previous','vw-education-academy' );?></span>
        </a>
        <a class="carousel-control-next" data-bs-target="#carouselExampleCaptions" data-bs-slide="next" role="button">
          <span class="carousel-control-next-icon w-auto h-auto" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
          <span class="screen-reader-text"><?php esc_html_e( 'Next','vw-education-academy' );?></span>
        </a>
      </div>
      <div class="clearfix"></div>
    <?php } else if(get_theme_mod('vw_education_academy_slider_type', 'Advance slider') == 'Advance slider'){?>
        <?php echo do_shortcode(get_theme_mod('vw_education_academy_advance_slider_shortcode')); ?>
    <?php } ?>
  </section>

  <?php } ?>

  <?php do_action( 'vw_education_academy_after_slider' ); ?>

  <section id="about-section" class="wow zoomInDown delay-1000" data-wow-duration="2s"> 
    <div class="container">
      <div class="row m-0">
        <?php $vw_education_academy_about_pages = array();
          $mod = absint( get_theme_mod( 'vw_education_academy_about_page' ));
          if ( 'page-none-selected' != $mod ) {
            $vw_education_academy_about_pages[] = $mod;
          }
          if( !empty($vw_education_academy_about_pages) ) :
            $args = array(
              'post_type' => 'page',
              'post__in' => $vw_education_academy_about_pages,
              'orderby' => 'postabout_page__in'
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
              while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="col-lg-7 col-md-7">
                  <?php if( get_theme_mod( 'vw_education_academy_section_title') != '') { ?>
                    <span><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_about_title_icon','fas fa-graduation-cap')); ?>"></i></span>
                    <h2><?php echo esc_html(get_theme_mod('vw_education_academy_section_title',''));?></h2>
                    <hr>
                  <?php }?>
                  <h3><?php the_title(); ?></h3>
                  <p><?php $vw_education_academy_excerpt = get_the_excerpt(); echo esc_html( vw_education_academy_string_limit_words( $vw_education_academy_excerpt, esc_attr(get_theme_mod('vw_education_academy_about_excerpt_number','30')))); ?></p>
                  <?php if( get_theme_mod('vw_education_academy_about_button_text','Read More') != ''){ ?>
                    <div class="more-btn">
                      <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_theme_mod('vw_education_academy_about_button_text',__('Read More','vw-education-academy')));?><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_about_button_icon','fa fa-angle-right')); ?>"></i><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_education_academy_about_button_text',__('Read More','vw-education-academy')));?></span></a>
                    </div>
                  <?php } ?>
                </div>
                <div class="col-lg-5 col-md-5">
                  <?php the_post_thumbnail(); ?>
                </div>
              <?php endwhile; ?>
            <?php else : ?>
              <div class="no-postfound"></div>
            <?php endif;
          endif;
          wp_reset_postdata()?>
        <div class="clearfix"></div> 
      </div>
    </div>
  </section>

  <?php do_action( 'vw_education_academy_after_services' ); ?>

  <div class="content-vw">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>