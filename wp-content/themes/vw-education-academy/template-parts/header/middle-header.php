<?php
/**
 * The template part for header
 *
 * @package VW Education Academy 
 * @subpackage vw_education_academy
 * @since VW Education Academy 1.0
 */
?>

<?php
  $vw_education_academy_search_hide_show = get_theme_mod( 'vw_education_academy_search_hide_show' );
  if ( 'Disable' == $vw_education_academy_search_hide_show ) {
   $colmd = 'col-lg-9 col-md-9';
  } else { 
   $colmd = 'col-lg-8 col-md-6 col-6';
  } 
?>

<div class="main-header">
  <div class="header-menu">
    <div class="container">
      <div class="box-header">
        <div class="row m-0">
          <div class="col-lg-3 col-md-4 position-relative">
            <div class="logo">
            <?php if ( has_custom_logo() ) : ?>
              <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            <?php $blog_info = get_bloginfo( 'name' ); ?>
              <?php if ( ! empty( $blog_info ) ) : ?>
                <?php if ( is_front_page() && is_home() ) : ?>
                  <?php if( get_theme_mod('vw_education_academy_logo_title_hide_show',true) == 1){ ?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                  <?php } ?>
                <?php else : ?>
                  <?php if( get_theme_mod('vw_education_academy_logo_title_hide_show',true) == 1){ ?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                  <?php } ?>
                <?php endif; ?>
              <?php endif; ?>
              <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
              ?>
              <?php if( get_theme_mod('vw_education_academy_tagline_hide_show',false) == 1){ ?>
                <p class="site-description">
                  <?php echo esc_html($description); ?>
                </p>
              <?php } ?>
            <?php endif; ?>
            </div>
          </div>
          <div class="<?php echo esc_html( $colmd ); ?> align-self-center">
            <?php get_template_part( 'template-parts/header/navigation' ); ?>
          </div>
          <?php if ( 'Disable' != $vw_education_academy_search_hide_show ) {?>
            <div class="col-lg-1 col-md-2 col-6 align-self-center">
              <div class="search-box">
                <span><a href="#"><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_search_icon','fas fa-search')); ?>"></i></a></span>
              </div>
            </div>
          <?php }?>
        </div>
        <div class="serach_outer">
          <div class="closepop"><a href="#maincontent"><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_search_close_icon','fa fa-window-close')); ?>"></i></a></div>
          <div class="serach_inner">
            <?php get_search_form(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>