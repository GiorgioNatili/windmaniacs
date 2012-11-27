<?php get_header(); ?>




	<div id="header-wrapper">
		<?php get_template_part( 'flexi-header' ); ?>
		<?php $slider_query = new WP_Query('post_type=slider&posts_per_page=-1'); ?>
		<?php if ($slider_query->have_posts()) : ?>
	    <div id="leader" class="container">
	        <div class="margin clearfix">
				<h1 class="page-special-title hide"><?php the_title(); ?></h1>
	            <div id="featured-slider" class="clearfix">
	                <div id="feature-wrapper">
	                <div id="features">
	                    <ul class="feature-list">
	                        <?php while ($slider_query->have_posts()) : $slider_query->the_post();
								$width_product = prima_get_option('sliderproductwidth') ? prima_get_option('sliderproductwidth') : 500;
								$width = (prima_get_option('themelayout') == 'boxed') ? 896 : 980;
								$height = prima_get_option('sliderheight') ? prima_get_option('sliderheight') : 375;
	                            $postid = get_the_ID();
	                            $slider_type = prima_get_custom_field("sliderType");
	                            if ($slider_type == 'simpleslider'){
	                                prima_slider_simple($postid, $width, $height);
	                            }
	                            elseif ($slider_type == 'product'){
	                                $slider_id = prima_get_custom_field("sliderProductId");
	                                prima_slider_product($slider_id, $width_product, $height);
	                            }
	                            elseif ($slider_type == 'news'){
	                                $slider_id = prima_get_custom_field("sliderPostId");
	                                prima_slider_post($slider_id, $width, $height);
	                            }
	                            elseif ($slider_type == 'promotion'){
	                                $slider_id = prima_get_custom_field("sliderPromotionId");
	                                prima_slider_promotion($slider_id, $width, $height);
	                            }
	                        endwhile;
	                        ?>
	                    </ul>
	                </div>
	                </div>
	            </div>
	            <div id="slider-controls"></div>
	        </div>
	    </div>
	    <?php endif; ?>
	</div>
	<div id="content-wrapper">
		<div id="main-content" class="container">
			<div class="margin">
				<div id="main-col">
					<?php
					    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
					    ?>

					    <h2>About: <?php echo $curauth->nickname; ?></h2>

					    <dl>
					        <dt>Website</dt>
					        <dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
					        <dt>Email</dt>
					        <dd><a href="mailto:<?php echo antispambot($curauth->user_email); ?>"><?php echo $curauth->user_email; ?></a></dd>
					        <dt>Profile</dt>
					        <dd><div class="profile-avatar"><?php echo get_avatar($curauth->ID, $size = '96'); ?></div><?php echo $curauth->user_description; ?></dd>
					    </dl>

					    <h2>Other product from: <?php echo $curauth->nickname; ?></h2>

					    <ul>
					<!-- The Loop -->
						<?php $authorID = $curauth->ID; ?>
						<?php 
						$query= 'author=' . $authorID. '&post_type=wpsc-product&orderby=post_date&order=desc'; // concatenate the query
				 		query_posts($query); // run the query
					    ?>
					    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					        <li>
					            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>">
					            <?php the_title(); ?></a>,
					            <?php the_time('d M Y'); ?> in <?php the_category('&');?>
					        </li>

					    <?php endwhile; else: ?>
					        <p><?php _e('No posts by this author.'); ?></p>

					    <?php endif; ?>

					<!-- End Loop -->

					    </ul>

				</div>
				<div id="sidebar">
					<div class="sidebar-inner">
						<?php get_sidebar(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php get_footer(); ?>

