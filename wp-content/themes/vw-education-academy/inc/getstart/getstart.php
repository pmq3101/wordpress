<?php
//about theme info
add_action( 'admin_menu', 'vw_education_academy_gettingstarted' );
function vw_education_academy_gettingstarted() {    	
	add_theme_page( esc_html__('About VW Education Academy', 'vw-education-academy'), esc_html__('About VW Education Academy', 'vw-education-academy'), 'edit_theme_options', 'vw_education_academy_guide', 'vw_education_academy_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function vw_education_academy_admin_theme_style() {
   wp_enqueue_style('vw-education-academy-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/getstart/getstart.css');
   wp_enqueue_script('vw-education-academy-tabs', esc_url(get_template_directory_uri()) . '/inc/getstart/js/tab.js');
}
add_action('admin_enqueue_scripts', 'vw_education_academy_admin_theme_style');

//guidline for about theme
function vw_education_academy_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme( 'vw-education-academy' );
?>

<div class="wrapper-info">
    <div class="col-left">
    	<h2><?php esc_html_e( 'Welcome to VW Education Academy Theme', 'vw-education-academy' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','vw-education-academy'); ?></p>
    </div>
    <div class="col-right">
    	<div class="logo">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/final-logo.png" alt="" />
		</div>
		<div class="update-now">
			<h4><?php esc_html_e('Buy VW Education Academy at 20% Discount','vw-education-academy'); ?></h4>
			<h4><?php esc_html_e('Use Coupon','vw-education-academy'); ?> ( <span><?php esc_html_e('vwpro20','vw-education-academy'); ?></span> ) </h4> 
			<div class="info-link">
				<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Upgrade to Pro', 'vw-education-academy' ); ?></a>
			</div>
		</div>
    </div>

    <div class="tab-sec">
		<div class="tab">
			<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'lite_theme')"><?php esc_html_e( 'Setup With Customizer', 'vw-education-academy' ); ?></button>	
			<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'block_pattern')"><?php esc_html_e( 'Setup With Block Pattern', 'vw-education-academy' ); ?></button>
			<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'gutenberg_editor')"><?php esc_html_e( 'Setup With Gutunberg Block', 'vw-education-academy' ); ?></button> 
			<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'product_addons_editor')"><?php esc_html_e( 'Woocommerce Product Addons', 'vw-education-academy' ); ?></button>
		  	<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'theme_pro')"><?php esc_html_e( 'Get Premium', 'vw-education-academy' ); ?></button>
		  	<button class="tablinks" onclick="vw_education_academy_open_tab(event, 'free_pro')"><?php esc_html_e( 'Support', 'vw-education-academy' ); ?></button>
		</div>

		<!-- Tab content -->
		<?php
			$vw_education_academy_plugin_custom_css = '';
			if(class_exists('Ibtana_Visual_Editor_Menu_Class')){
				$vw_education_academy_plugin_custom_css ='display: block';
			}
		?>
		<div id="lite_theme" class="tabcontent open">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = VW_Education_Academy_Plugin_Activation_Settings::get_instance();
				$vw_education_academy_actions = $plugin_ins->recommended_actions;
				?>
				<div class="vw-education-academy-recommended-plugins">
				    <div class="vw-education-academy-action-list">
				        <?php if ($vw_education_academy_actions): foreach ($vw_education_academy_actions as $key => $vw_education_academy_actionValue): ?>
				                <div class="vw-education-academy-action" id="<?php echo esc_attr($vw_education_academy_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($vw_education_academy_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($vw_education_academy_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($vw_education_academy_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" get-start-tab-id="lite-theme-tab" href="javascript:void(0);"><?php esc_html_e('Skip','vw-education-academy'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="lite-theme-tab" style="<?php echo esc_attr($vw_education_academy_plugin_custom_css); ?>">
				<h3><?php esc_html_e( 'Lite Theme Information', 'vw-education-academy' ); ?></h3>
				<hr class="h3hr">
			  	<p><?php esc_html_e('VW Education Academy is a sophisticated, youthful, robust and feature-full academic WordPress theme that will make learning and teaching a pleasant and fun experience for both teachers and students. It is an ideal theme for all types of teaching and educational websites whether it is for high-school, college, university, training academy, coaching centre, LMS, guidance and counselling centre, institute, distance learning, online library or discussion forum. It is highly customizable so you can change its colour scheme, fonts, images and background to use it for kindergartens, playschools, day care centres, primary schools and similar websites. It has an intuitive design and super-flexible layout that can be changed from boxed to full-width to full-screen. Since VW Education Academy is fully responsive, it makes your content accessible from any device. The use of social media icons makes content shareable on various networking platforms making your content further easy to access. The theme is all-browser compatible, multilingual, retina ready and supports RTL writing style. It is built on the recently launched WordPress version resulting in a modern and updated website that will work smoothly for years. There are many inner pages and several sections predesigned to serve needs of academic website.','vw-education-academy'); ?></p>
			  	<div class="col-left-inner">
			  		<h4><?php esc_html_e( 'Theme Documentation', 'vw-education-academy' ); ?></h4>
					<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'vw-education-academy' ); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'vw-education-academy' ); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Theme Customizer', 'vw-education-academy'); ?></h4>
					<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'vw-education-academy'); ?></p>
					<div class="info-link">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'vw-education-academy'); ?></a>
					</div>
					<hr>				
					<h4><?php esc_html_e('Having Trouble, Need Support?', 'vw-education-academy'); ?></h4>
					<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'vw-education-academy'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'vw-education-academy'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Reviews & Testimonials', 'vw-education-academy'); ?></h4>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'vw-education-academy'); ?>  </p>
					<div class="info-link">
						<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'vw-education-academy'); ?></a>
					</div>
			  		<div class="link-customizer">
						<h3><?php esc_html_e( 'Link to customizer', 'vw-education-academy' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','vw-education-academy'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-welcome-write-blog"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_topbar') ); ?>" target="_blank"><?php esc_html_e('Topbar Settings','vw-education-academy'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-slides"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Section','vw-education-academy'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','vw-education-academy'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','vw-education-academy'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-admin-customizer"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=vw_education_academy_typography') ); ?>" target="_blank"><?php esc_html_e('Typography','vw-education-academy'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','vw-education-academy'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_woocommerce_section') ); ?>" target="_blank"><?php esc_html_e('WooCommerce Layout','vw-education-academy'); ?></a>
								</div> 
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','vw-education-academy'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','vw-education-academy'); ?></a>
								</div> 
							</div>
						</div>
					</div>
			  	</div>
				<div class="col-right-inner">
					<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','vw-education-academy'); ?></h3>
				  	<hr class="h3hr">
					<p><?php esc_html_e('Follow these instructions to setup Home page.','vw-education-academy'); ?></p>
	                <ul>
	                  	<p><span class="strong"><?php esc_html_e('1. Create a new page :','vw-education-academy'); ?></span><?php esc_html_e(' Go to ','vw-education-academy'); ?>
					  	<b><?php esc_html_e(' Dashboard >> Pages >> Add New Page','vw-education-academy'); ?></b></p>

	                  	<p><?php esc_html_e('Name it as "Home" then select the template "Custom Home Page".','vw-education-academy'); ?></p>
	                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
	                  	<p><span class="strong"><?php esc_html_e('2. Set the front page:','vw-education-academy'); ?></span><?php esc_html_e(' Go to ','vw-education-academy'); ?>
					  	<b><?php esc_html_e(' Settings >> Reading ','vw-education-academy'); ?></b></p>
					  	<p><?php esc_html_e('Select the option of Static Page, now select the page you created to be the homepage, while another page to be your default page.','vw-education-academy'); ?></p>
	                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
	                  	<p><?php esc_html_e(' Once you are done with this, then follow the','vw-education-academy'); ?> <a class="doc-links" href="https://www.vwthemesdemo.com/docs/free-vw-education-academy/" target="_blank"><?php esc_html_e('Documentation','vw-education-academy'); ?></a></p>
	                </ul>
			  	</div>
			</div>
		</div>	

		<div id="block_pattern" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = VW_Education_Academy_Plugin_Activation_Settings::get_instance();
				$vw_education_academy_actions = $plugin_ins->recommended_actions;
				?>
				<div class="vw-education-academy-recommended-plugins">
				    <div class="vw-education-academy-action-list">
				        <?php if ($vw_education_academy_actions): foreach ($vw_education_academy_actions as $key => $vw_education_academy_actionValue): ?>
				                <div class="vw-education-academy-action" id="<?php echo esc_attr($vw_education_academy_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($vw_education_academy_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($vw_education_academy_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($vw_education_academy_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" href="javascript:void(0);" get-start-tab-id="gutenberg-editor-tab"><?php esc_html_e('Skip','vw-education-academy'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="gutenberg-editor-tab" style="<?php echo esc_attr($vw_education_academy_plugin_custom_css); ?>">
				<div class="block-pattern-img">
				  	<h3><?php esc_html_e( 'Block Patterns', 'vw-education-academy' ); ?></h3>
					<hr class="h3hr">
					<p><?php esc_html_e('Follow the below instructions to setup Home page with Block Patterns.','vw-education-academy'); ?></p>
	              	<p><b><?php esc_html_e('Click on Below Add new page button >> Click on "+" Icon.','vw-education-academy'); ?></span></b></p>
	              	<div class="vw-education-academy-pattern-page">
				    	<a href="javascript:void(0)" class="vw-pattern-page-btn button-primary button"><?php esc_html_e('Add New Page','vw-education-academy'); ?></a>
				    </div>
				    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/block-pattern1.png" alt="" />
				    	<p><b><?php esc_html_e('Click on Patterns Tab >> Click on Theme Name >> Click on Sections >> Publish.','vw-education-academy'); ?></span></b></p>
	              	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/block-pattern.png" alt="" />
	            </div>

	            <div class="block-pattern-link-customizer">
	              	<div class="link-customizer-with-block-pattern">
						<h3><?php esc_html_e( 'Link to customizer', 'vw-education-academy' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','vw-education-academy'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-networking"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_social_icon_settings') ); ?>" target="_blank"><?php esc_html_e('Social Icons','vw-education-academy'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','vw-education-academy'); ?></a>
								</div>
								
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','vw-education-academy'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','vw-education-academy'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_woocommerce_section') ); ?>" target="_blank"><?php esc_html_e('WooCommerce Layout','vw-education-academy'); ?></a>
								</div> 
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','vw-education-academy'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','vw-education-academy'); ?></a>
								</div> 
							</div>
						</div>
					</div>	
				</div>					
	        </div>
		</div>

		<div id="gutenberg_editor" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = VW_Education_Academy_Plugin_Activation_Settings::get_instance();
			$vw_education_academy_actions = $plugin_ins->recommended_actions;
			?>
				<div class="vw-education-academy-recommended-plugins">
				    <div class="vw-education-academy-action-list">
				        <?php if ($vw_education_academy_actions): foreach ($vw_education_academy_actions as $key => $vw_education_academy_actionValue): ?>
				                <div class="vw-education-academy-action" id="<?php echo esc_attr($vw_education_academy_actionValue['id']);?>">
			                        <div class="action-inner plugin-activation-redirect">
			                            <h3 class="action-title"><?php echo esc_html($vw_education_academy_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($vw_education_academy_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($vw_education_academy_actionValue['link']); ?>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Gutunberg Blocks', 'vw-education-academy' ); ?></h3>
				<hr class="h3hr">
				<div class="vw-education-academy-pattern-page">
			    	<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-templates' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Ibtana Settings','vw-education-academy'); ?></a>
			    </div>

			    <div class="link-customizer-with-guternberg-ibtana">
					<h3><?php esc_html_e( 'Link to customizer', 'vw-education-academy' ); ?></h3>
					<hr class="h3hr">
					<div class="first-row">
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','vw-education-academy'); ?></a>
							</div>
							<div class="row-box2">
								<span class="dashicons dashicons-networking"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_social_icon_settings') ); ?>" target="_blank"><?php esc_html_e('Social Icons','vw-education-academy'); ?></a>
							</div>
						</div>
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','vw-education-academy'); ?></a>
							</div>
							
							<div class="row-box2">
								<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','vw-education-academy'); ?></a>
							</div>
						</div>

						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','vw-education-academy'); ?></a>
							</div>
							 <div class="row-box2">
								<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_woocommerce_section') ); ?>" target="_blank"><?php esc_html_e('WooCommerce Layout','vw-education-academy'); ?></a>
							</div> 
						</div>
						
						<div class="row-box">
							<div class="row-box1">
								<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=vw_education_academy_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','vw-education-academy'); ?></a>
							</div>
							 <div class="row-box2">
								<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','vw-education-academy'); ?></a>
							</div> 
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<div id="product_addons_editor" class="tabcontent">
			<?php if(!class_exists('IEPA_Loader')){
				$plugin_ins = VW_Education_Academy_Plugin_Activation_Woo_Products::get_instance();
				$vw_education_academy_actions = $plugin_ins->recommended_actions;
				?>
				<div class="vw-education-academy-recommended-plugins">
					    <div class="vw-education-academy-action-list">
					        <?php if ($vw_education_academy_actions): foreach ($vw_education_academy_actions as $key => $vw_education_academy_actionValue): ?>
					                <div class="vw-education-academy-action" id="<?php echo esc_attr($vw_education_academy_actionValue['id']);?>">
				                        <div class="action-inner plugin-activation-redirect">
				                            <h3 class="action-title"><?php echo esc_html($vw_education_academy_actionValue['title']); ?></h3>
				                            <div class="action-desc"><?php echo esc_html($vw_education_academy_actionValue['desc']); ?></div>
				                            <?php echo wp_kses_post($vw_education_academy_actionValue['link']); ?>
				                        </div>
					                </div>
					            <?php endforeach;
					        endif; ?>
					    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Woocommerce Products Blocks', 'vw-education-academy' ); ?></h3>
				<hr class="h3hr">
				<div class="vw-education-academy-pattern-page">
					<p><?php esc_html_e('Follow the below instructions to setup Products Templates.','vw-education-academy'); ?></p>
					<p><b><?php esc_html_e('1. First you need to activate these plugins','vw-education-academy'); ?></b></p>
						<p><?php esc_html_e('1. Ibtana - WordPress Website Builder ','vw-education-academy'); ?></p>
						<p><?php esc_html_e('2. Ibtana - Ecommerce Product Addons.','vw-education-academy'); ?></p>
						<p><?php esc_html_e('3. Woocommerce','vw-education-academy'); ?></p>

					<p><b><?php esc_html_e('2. Go To Dashboard >> Ibtana Settings >> Woocommerce Templates','vw-education-academy'); ?></span></b></p>
	              	<div class="vw-education-academy-pattern-page">
			    		<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-woocommerce-templates&ive_wizard_view=parent' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Woocommerce Templates','vw-education-academy'); ?></a>
			    	</div>
	              	<p><?php esc_html_e('You can create a template as you like.','vw-education-academy'); ?></span></p>
			    </div>
			<?php } ?>
		</div>

		<div id="theme_pro" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'vw-education-academy' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('This academic WordPress theme is engaging, entertaining and clean, just the way an educational website should be. It is developed with all the advanced functionality and loaded with some super awesome features for fast and easy development of website. Although it has many amazing components and performance oriented functions, but your teaching website will never bloat or break. Its flexible layout and intuitive design suits high schools, colleges and universities as well as elementary schools, kindergartens and day care centres. The functionality of this academic WordPress theme can be extended with the help of third party plugins. It is coded with WordPress standards and based on the recently released WordPress version to make a bug-free and updated website that will be robust and reliable for a long time. It is thoroughly documented so anyone with no coding knowledge can also design an efficient website without writing a single line of code.','vw-education-academy'); ?></p>
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'vw-education-academy'); ?></a>
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Pro', 'vw-education-academy'); ?></a>
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'vw-education-academy'); ?></a>
				</div>
		    </div>
		    <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		    <div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'vw-education-academy' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'vw-education-academy'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'vw-education-academy'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'vw-education-academy'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'vw-education-academy'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'vw-education-academy'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Theme sections', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('2', 'vw-education-academy'); ?></td>
								<td class="table-img"><?php esc_html_e('15', 'vw-education-academy'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Contact us Page Template', 'vw-education-academy'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'vw-education-academy'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Blog Templates & Layout', 'vw-education-academy'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'vw-education-academy'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Page Templates & Layout', 'vw-education-academy'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'vw-education-academy'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Color Pallete For Particular Sections', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Global Color Option', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Reordering', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Demo Importer', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Allow To Set Site Title, Tagline, Logo', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Enable Disable Options On All Sections, Logo', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Full Documentation', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Latest WordPress Compatibility', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support 3rd Party Plugins', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Secure and Optimized Code', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Exclusive Functionalities', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Enable / Disable', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Google Font Choices', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Gallery', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Simple & Mega Menu Option', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Shortcodes', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Premium Membership', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Budget Friendly Value', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Priority Error Fixing', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Feature Addition', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('All Access Theme Pass', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Seamless Customer Support', 'vw-education-academy'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'vw-education-academy'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="free_pro" class="tabcontent">
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-star-filled"></span><?php esc_html_e('Pro Version', 'vw-education-academy'); ?></h4>
				<p> <?php esc_html_e('To gain access to extra theme options and more interesting features, upgrade to pro version.', 'vw-education-academy'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'vw-education-academy'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-cart"></span><?php esc_html_e('Pre-purchase Queries', 'vw-education-academy'); ?></h4>
				<p> <?php esc_html_e('If you have any pre-sale query, we are prepared to resolve it.', 'vw-education-academy'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_CONTACT ); ?>" target="_blank"><?php esc_html_e('Question', 'vw-education-academy'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">		  		
		  		<h4><span class="dashicons dashicons-admin-customizer"></span><?php esc_html_e('Child Theme', 'vw-education-academy'); ?></h4>
				<p> <?php esc_html_e('For theme file customizations, make modifications in the child theme and not in the main theme file.', 'vw-education-academy'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_CHILD_THEME ); ?>" target="_blank"><?php esc_html_e('About Child Theme', 'vw-education-academy'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e('Frequently Asked Questions', 'vw-education-academy'); ?></h4>
				<p> <?php esc_html_e('We have gathered top most, frequently asked questions and answered them for your easy understanding. We will list down more as we get new challenging queries. Check back often.', 'vw-education-academy'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_FAQ ); ?>" target="_blank"><?php esc_html_e('View FAQ','vw-education-academy'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-sos"></span><?php esc_html_e('Support Queries', 'vw-education-academy'); ?></h4>
				<p> <?php esc_html_e('If you have any queries after purchase, you can contact us. We are eveready to help you out.', 'vw-education-academy'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( VW_EDUCATION_ACADEMY_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Contact Us', 'vw-education-academy'); ?></a>
				</div>
		  	</div>
		</div>
	</div>
</div>
<?php } ?>