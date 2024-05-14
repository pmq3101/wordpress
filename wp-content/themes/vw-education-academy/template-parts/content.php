<?php
/**
 * The template part for displaying post
 *
 * @package VW Education Academy 
 * @subpackage vw_education_academy
 * @since VW Education Academy 1.0
 */
?>
<?php 
  $vw_education_academy_archive_year  = get_the_time('Y'); 
  $vw_education_academy_archive_month = get_the_time('m'); 
  $vw_education_academy_archive_day   = get_the_time('d'); 
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <div class="post-main-box wow slideInLeft delay-1000" data-wow-duration="2s">
    <?php $vw_education_academy_theme_lay = get_theme_mod( 'vw_education_academy_blog_layout_option','Default');
    if($vw_education_academy_theme_lay == 'Default'){ ?>
      <div class="row m-0">
        <?php if(has_post_thumbnail() && get_theme_mod( 'vw_education_academy_featured_image_hide_show',true) == 1) {?>
          <div class="box-image col-lg-6 col-md-6">
            <?php the_post_thumbnail(); ?>
          </div>
        <?php } ?>
        <div class="new-text <?php if(has_post_thumbnail() && get_theme_mod( 'vw_education_academy_featured_image_hide_show',true) == 1) { ?>col-lg-6 col-md-6"<?php } else { ?>col-lg-12 col-md-12"<?php } ?>>
          <h2 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title();?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
          <?php if( get_theme_mod( 'vw_education_academy_toggle_postdate',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_author',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_comments',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_time',true) == 1) { ?>
            <div class="post-info">
              <?php if(get_theme_mod('vw_education_academy_toggle_postdate',true)==1){ ?>
                  <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_postdate_icon','fas fa-calendar-alt')); ?>"></i><span class="entry-date"><a href="<?php echo esc_url( get_day_link( $vw_education_academy_archive_year, $vw_education_academy_archive_month, $vw_education_academy_archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
                <?php } ?>

                <?php if(get_theme_mod('vw_education_academy_toggle_author',true)==1){ ?>
                   <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_author_icon','far fa-user')); ?>"></i><span class="entry-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
                <?php } ?>

                <?php if(get_theme_mod('vw_education_academy_toggle_comments',true)==1){ ?>
                   <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_comments_icon','fas fa-comments')); ?>"></i><span class="entry-comments"><?php comments_number( __('0 Comment', 'vw-education-academy'), __('0 Comments', 'vw-education-academy'), __('% Comments', 'vw-education-academy') ); ?> </span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
                <?php } ?>

                <?php if(get_theme_mod('vw_education_academy_toggle_time',true)==1){ ?>
                   <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_time_icon','far fa-clock')); ?>"></i><span class="entry-time"><?php echo esc_html( get_the_time() ); ?></span>
                <?php } ?>
                <?php echo esc_html (vw_education_academy_edit_link()); ?>
              <hr>
            </div>
          <?php } ?>
          <div class="entry-content">
            <p>
              <?php $vw_education_academy_theme_lay = get_theme_mod( 'vw_education_academy_excerpt_settings','Excerpt');
              if($vw_education_academy_theme_lay == 'Content'){ ?>
                <?php the_content(); ?>
              <?php }
              if($vw_education_academy_theme_lay == 'Excerpt'){ ?>
                <?php if(get_the_excerpt()) { ?>
                  <?php $vw_education_academy_excerpt = get_the_excerpt(); echo esc_html( vw_education_academy_string_limit_words( $vw_education_academy_excerpt, esc_attr(get_theme_mod('vw_education_academy_excerpt_number','30')))); ?> <?php echo esc_html(get_theme_mod('vw_education_academy_excerpt_suffix',''));?>
                <?php }?>
              <?php }?>
            </p>
          </div>
          <?php if( get_theme_mod('vw_education_academy_button_text','Read More') != ''){ ?>
            <div class="content-bttn">
              <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_blog_button_icon','fa fa-angle-right')); ?>"></i><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?></span></a>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php }else if($vw_education_academy_theme_lay == 'Center'){ ?>
      <div class="service-text">
        <h2 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title();?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
        <?php if( get_theme_mod( 'vw_education_academy_featured_image_hide_show',true) == 1) { ?>
          <div class="box-image">
            <?php the_post_thumbnail(); ?>
          </div>
        <?php } ?>
        <?php if( get_theme_mod( 'vw_education_academy_toggle_postdate',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_author',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_comments',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_time',true) == 1) { ?>
          <div class="post-info">
            <?php if(get_theme_mod('vw_education_academy_toggle_postdate',true)==1){ ?>
                <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_postdate_icon','fas fa-calendar-alt')); ?>"></i><span class="entry-date"><a href="<?php echo esc_url( get_day_link( $vw_education_academy_archive_year, $vw_education_academy_archive_month, $vw_education_academy_archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_author',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_author_icon','far fa-user')); ?>"></i><span class="entry-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_comments',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_comments_icon','fas fa-comments')); ?>"></i><span class="entry-comments"><?php comments_number( __('0 Comment', 'vw-education-academy'), __('0 Comments', 'vw-education-academy'), __('% Comments', 'vw-education-academy') ); ?> </span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_time',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_time_icon','far fa-clock')); ?>"></i><span class="entry-time"><?php echo esc_html( get_the_time() ); ?></span>
              <?php } ?>
              <?php echo esc_html (vw_education_academy_edit_link()); ?>
            <hr>
          </div>
        <?php } ?>
        <div class="entry-content">
          <p>
            <?php $vw_education_academy_theme_lay = get_theme_mod( 'vw_education_academy_excerpt_settings','Excerpt');
            if($vw_education_academy_theme_lay == 'Content'){ ?>
              <?php the_content(); ?>
            <?php }
            if($vw_education_academy_theme_lay == 'Excerpt'){ ?>
              <?php if(get_the_excerpt()) { ?>
                <?php $vw_education_academy_excerpt = get_the_excerpt(); echo esc_html( vw_education_academy_string_limit_words( $vw_education_academy_excerpt, esc_attr(get_theme_mod('vw_education_academy_excerpt_number','30')))); ?> <?php echo esc_html(get_theme_mod('vw_education_academy_excerpt_suffix',''));?>
              <?php }?>
            <?php }?>
          </p>
        </div>
        <?php if( get_theme_mod('vw_education_academy_button_text','Read More') != ''){ ?>
          <div class="content-bttn">
            <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_blog_button_icon','fa fa-angle-right')); ?>"></i><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?></span></a>
          </div>
        <?php } ?>
      </div>
    <?php }else if($vw_education_academy_theme_lay == 'Left'){ ?>
      <div class="service-text">
        <?php if( get_theme_mod( 'vw_education_academy_featured_image_hide_show',true) == 1) { ?>
          <div class="box-image">
            <?php the_post_thumbnail(); ?>
          </div>
        <?php } ?>
        <h2 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title();?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
        <?php if( get_theme_mod( 'vw_education_academy_toggle_postdate',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_author',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_comments',true) == 1 || get_theme_mod( 'vw_education_academy_toggle_time',true) == 1) { ?>
          <div class="post-info">
            <?php if(get_theme_mod('vw_education_academy_toggle_postdate',true)==1){ ?>
                <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_postdate_icon','fas fa-calendar-alt')); ?>"></i><span class="entry-date"><a href="<?php echo esc_url( get_day_link( $vw_education_academy_archive_year, $vw_education_academy_archive_month, $vw_education_academy_archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_author',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_author_icon','far fa-user')); ?>"></i><span class="entry-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_comments',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_comments_icon','fas fa-comments')); ?>"></i><span class="entry-comments"><?php comments_number( __('0 Comment', 'vw-education-academy'), __('0 Comments', 'vw-education-academy'), __('% Comments', 'vw-education-academy') ); ?> </span><span><?php echo esc_html(get_theme_mod('vw_education_academy_meta_field_separator', '|'));?></span>
              <?php } ?>

              <?php if(get_theme_mod('vw_education_academy_toggle_time',true)==1){ ?>
                 <i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_toggle_time_icon','far fa-clock')); ?>"></i><span class="entry-time"><?php echo esc_html( get_the_time() ); ?></span>
              <?php } ?>
              <?php echo esc_html (vw_education_academy_edit_link()); ?>
            <hr>
          </div>
        <?php } ?>
        <div class="entry-content">
          <p>
            <?php $vw_education_academy_theme_lay = get_theme_mod( 'vw_education_academy_excerpt_settings','Excerpt');
            if($vw_education_academy_theme_lay == 'Content'){ ?>
              <?php the_content(); ?>
            <?php }
            if($vw_education_academy_theme_lay == 'Excerpt'){ ?>
              <?php if(get_the_excerpt()) { ?>
                <?php $vw_education_academy_excerpt = get_the_excerpt(); echo esc_html( vw_education_academy_string_limit_words( $vw_education_academy_excerpt, esc_attr(get_theme_mod('vw_education_academy_excerpt_number','30')))); ?> <?php echo esc_html(get_theme_mod('vw_education_academy_excerpt_suffix',''));?>
              <?php }?>
            <?php }?>
          </p>
        </div>
        <?php if( get_theme_mod('vw_education_academy_button_text','Read More') != ''){ ?>
          <div class="content-bttn">
            <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?><i class="<?php echo esc_attr(get_theme_mod('vw_education_academy_blog_button_icon','fa fa-angle-right')); ?>"></i><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_education_academy_button_text',__('Read More','vw-education-academy')));?></span></a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
</article>