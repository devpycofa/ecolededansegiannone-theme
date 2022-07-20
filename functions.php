<?php

/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts()
{
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
	// CUSTOM JS
	// wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/src/js/index.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/src/js/index.js', array('jquery'), rand(1, 100), true);
	// SLICK CAROUSEL
	wp_register_script('Slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', null, null, true);
	wp_enqueue_script('Slick');
	wp_register_style('Slick CSS', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
	wp_enqueue_style('Slick CSS');
	wp_register_style('Slick CSS Themed', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
	wp_enqueue_style('Slick CSS Themed');
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);

require get_theme_file_path('/inc/single-event-shortcodes.php');

function display_events($attributes)
{
	ob_start();

	$shortcode_args = shortcode_atts(
		array(
			'stage' => 'false',
			'soiree' => 'false',
		),
		$attributes
	);

	$is_stage = $shortcode_args['stage'] === 'true' ? true : false;
	$is_soiree = $shortcode_args['soiree'] === 'true' ? true : false;

	$query_args = array(
		'post_type' => 'evenement',
		'posts_per_page' => -1,
		'meta_key'  => 'details_date_de_debut',
		'orderby'   => 'meta_value_num',
		'order'     => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => array($is_soiree ? 'soiree' : 'stage'),
			)
		)
	);
	// $query_args = array(
	// 	'post_type' => 'evenement',
	// 	'meta_key' => 'date_de_debut',
	// 	'posts_per_page' => -1,
	// 	'orderby' => 'title',
	// 	'order' => 'ASC',
	// 	'tax_query' => array(
	// 		array(
	// 			'taxonomy' => 'category',
	// 			'field' => 'slug',
	// 			'terms' => array($is_soiree ? 'soiree' : 'stage'),
	// 		)
	// 	)
	// );

	$events_query = new WP_Query($query_args);
	$red_tint = $is_soiree ? '#BB2332' : '#E75950';

	if ($events_query->have_posts()) : ?>
		<style>
			.events-container {
				display: flex;
				flex-direction: column;
				row-gap: 76px;
				margin-bottom: 96px;
			}

			.event {
				display: flex;
				flex-direction: row;
				/* align-items: center; */
				align-items: stretch;
				justify-content: flex-start;
				margin: 0 auto;
				width: 100%;
				max-width: 1364px;
				border: 2px solid;
				box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
			}

			.event-content {
				padding: 33px 33px 36px 33px;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				width: 100%;
				flex-grow: 1;
			}

			.event-content h3 {
				margin: 0;
				font-family: 'Barlow', sans-serif;
				font-size: 25px;
				text-transform: uppercase;
				margin-bottom: 16px;
			}

			.event-date-link {
				width: 100%;
				max-width: 260px;
			}

			.event-date {
				width: 100%;
				max-width: 260px;
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				padding: 0 8px;
			}

			.event-date span {
				color: white;
				width: 100%;
				/* display: inline-block;
				overflow-wrap: break-word; */
				overflow: hidden;
				font-size: 50px;
				text-transform: uppercase;
				line-height: 1.3em;
				font-family: 'Barlow'sans-serif;
				text-align: center;
			}

			.event-date span:first-child {
				font-weight: 700;
			}

			.event-date span:nth-child(2) {
				font-weight: 200;
			}

			.event-image {
				width: 100%;
				max-width: 400px;
				height: 290px;
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
				position: relative;
			}

			.event-image-overlay {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				opacity: 50%;
			}

			.event-image-link {
				width: 100%;
				max-width: 400px;
			}

			.event-link a {
				padding: 20px 30px;
				font-size: 15px;
				font-weight: 700;
				font-family: 'Barlow', sans-serif;
				min-width: 178px;
				color: #fff;
				text-transform: uppercase;
			}

			.event-link a:hover {
				background-color: #000 !important;
			}
		</style>
		<div class="events-container">
			<?php
			while ($events_query->have_posts()) :
				$events_query->the_post();
			?>
				<div class="event" style="border-color: <?php esc_html_e($red_tint); ?>;">
					<a class="event-image-link" href="<?php the_permalink(); ?>">
						<div class="event-image" style="background-image: url('<?php esc_html_e(get_field('images')['image_listing_evenements'] ?? ''); ?>');">
							<div class="event-image-overlay" style="background-color: <?php esc_html_e($red_tint); ?>;"></div>
						</div>
					</a>
					<div class="event-content">
						<div>
							<a href="<?php the_permalink(); ?>">
								<h3 style=" color: <?php esc_html_e($red_tint); ?>;"><?php the_title(); ?></h3>
							</a>
							<p class="event-location">
								<?php echo get_field('lieu')['adresse'] ? get_field('lieu')['adresse'] : '<span>Informations à venir</span>'; ?>
							</p>
						</div>
						<div class="event-link">
							<a style="background-color: <?php echo $red_tint; ?>;" href="<?php the_permalink(); ?>">Voir les détails</a>
						</div>
					</div>
					<div class="event-date" style="background-color: <?php esc_html_e($red_tint); ?>;">
						<?php
						$date_debut = get_field('details')['date_de_debut'];
						if ($date_debut) {
							$year = mb_substr(get_field('details')['date_de_debut'], -4);
							$day_month = str_replace($year, '', $date_debut);
							if (strpos($day_month, 'janvier')) $formatted_day_month =  str_replace('janvier', 'janv.', $day_month);
							elseif (strpos($day_month, 'février')) $formatted_day_month =  str_replace('février', 'févr.', $day_month);
							elseif (strpos($day_month, 'mars')) $formatted_day_month =  str_replace('mars', 'mars', $day_month);
							elseif (strpos($day_month, 'avril')) $formatted_day_month =  str_replace('avril', 'avr.', $day_month);
							elseif (strpos($day_month, 'mai')) $formatted_day_month =  str_replace('mai', 'mai', $day_month);
							elseif (strpos($day_month, 'juin')) $formatted_day_month =  str_replace('juin', 'juin', $day_month);
							elseif (strpos($day_month, 'juillet')) $formatted_day_month =  str_replace('juillet', 'juil.', $day_month);
							elseif (strpos($day_month, 'août')) $formatted_day_month =  str_replace('août', 'août', $day_month);
							elseif (strpos($day_month, 'septembre')) $formatted_day_month =  str_replace('septembre', 'sept.', $day_month);
							elseif (strpos($day_month, 'octobre')) $formatted_day_month =  str_replace('octobre', 'oct.', $day_month);
							elseif (strpos($day_month, 'novembre')) $formatted_day_month =  str_replace('novembre', 'nov.', $day_month);
							elseif (strpos($day_month, 'décembre')) $formatted_day_month =  str_replace('décembre', 'déc.', $day_month);
							else $formatted_day_month = $day_month;
							echo '<span>' . $formatted_day_month . '</span>';
							echo '<span>' . $year . '</span>';
						} else {
							echo '<span>À</span>';
							echo '<span>Venir</span>';
						}
						?>
					</div>
				</div>
			<?php
			endwhile;
		endif;

		wp_reset_postdata();

		return ob_get_clean();
	}
	add_shortcode('events', 'display_events');

	function display_dances_grid($attributes)
	{
		ob_start();
		$shortcode_args = shortcode_atts(array(
			'individuelles' => 'true',
			'couple' => 'false',
			'fitness' => 'false',
		), $attributes);

		$individuelles = $shortcode_args['individuelles'] === 'true' ? 'danses-individuelles' : '';
		$couple = $shortcode_args['couple'] === 'true' ? 'danses-en-couple	' : '';
		$fitness = $shortcode_args['fitness'] === 'true' ? 'danses-fitness	' : '';
		$tax_query_args = array($individuelles, $couple, $fitness);

		$query_args = array(
			'post_type' => 'discipline',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => $tax_query_args,
				)
			)
		);

		$disciplines = new WP_Query($query_args);

		if ($disciplines->have_posts()) {
			?>
			<style>
				.dances-grid {
					display: grid;
					grid-template-columns: repeat(3, minmax(0, 1fr));
					grid-gap: 120px;
					margin-bottom: 120px;
					width: 1120px;
					margin: 0 auto;
				}

				.discipline-container {
					display: flex;
					flex-direction: column;
					align-items: center;
				}

				.discipline-grid-image {
					width: 293px;
					height: 293px;
					background-size: cover !important;
					background-position: center !important;
					background-repeat: no-repeat !important;
					background: #f7f7f7;
					display: flex;
					justify-content: center;
					align-items: flex-end;
					/* border-bottom: 2px solid red; */
					box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
				}

				.discipline-title {
					/* background-color: #BB2332; */
					width: 80%;
					text-align: center;
					color: #fff;
					font-size: 34px;
					text-transform: uppercase;
					display: flex;
					justify-content: center;
					align-items: center;
					height: 110px;
				}

				.discipline-title h3 {
					margin: 0;
					letter-spacing: -0px;
				}

				.discipline-public {
					width: 80%;
					color: #fff;
					height: 64px;
					font-size: 34px;
					font-size: 25px;
					/* background-color: #BB2332; */
					box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
					text-align: center;
					display: flex;
					justify-content: center;
					align-items: center;
					padding: 14px 0 20px 0;
				}
			</style>
			<div class="dances-grid">
				<?php
				$i = 0;
				while ($disciplines->have_posts()) {
					$i++;
					$disciplines->the_post();
					$discipline_id = get_the_ID();
					$discipline_title = get_the_title();
					$discipline_image = get_field('image_grille', $discipline_id) ?? '';
					$discipline_name_first_line = get_field('intitule_grille', $discipline_id)['ligne_1'] ?? '';
					$discipline_name_second_line = get_field('intitule_grille', $discipline_id)['ligne_2'] ?? '';
					$isEven = $i % 2 == 0;
					$red_tranparent = !$isEven ? 'rgba(187, 35, 50, 0.5)' : 'rgba(239, 89, 80, 0.5)';
					$red = !$isEven ? 'rgba(187, 35, 50, 1)' : 'rgba(239, 89, 80, 1)';
					$red_line = $isEven ? 'rgba(187, 35, 50, 1)' : 'rgba(239, 89, 80, 1)';
					$tags = wp_get_post_tags($discipline_id);
				?>
					<a href="<?php echo esc_html_e(the_permalink()); ?>">
						<div class="discipline-container">
							<div class="discipline-grid-image" style="background-image: url('<?php echo $discipline_image; ?>') !important; border-bottom: 2px solid <?php echo $red_line; ?>;">
								<div class="discipline-title" style="background: <?php echo $red_tranparent; ?>;">
									<?php if ($discipline_name_first_line !== '' and $discipline_name_second_line !== '') : ?>
										<h3>
											<span style="font-weight: 200;"><?php echo esc_html_e($discipline_name_first_line) . '<br />'; ?></span>
											<b><?php echo esc_html_e($discipline_name_second_line); ?></b>
										</h3>
									<?php endif; ?>
									<?php if (($discipline_name_first_line === '' and $discipline_name_second_line !== '') or ($discipline_name_first_line === '' and $discipline_name_second_line === '')) : ?>
										<h3>
											<b><?php echo esc_html_e($discipline_title); ?></b>
										</h3>
									<?php endif; ?>
								</div>
							</div>
							<div class="discipline-public" style="background: <?php echo esc_html_e($red); ?>;">
								<?php
								$t = 0;
								foreach ($tags as $tag) :
									echo '<span>' . esc_html_e($tag->name) . '</span>';
									if (++$t !== count($tags)) {
										echo "<span class='coma'>,</span>";
									}
								endforeach;
								?>
							</div>
						</div>
					</a>
				<?php
				}
				?>
			</div>
		<?php
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	add_shortcode('dances_grid', 'display_dances_grid');

	// function display_profs_grid()
	// {
	// 	$args = array(
	// 		'post_type' => 'professor',
	// 		'posts_per_page' => -1,
	// 	);
	// 	$professors = new WP_Query($args);
	// 	while ($professors->have_posts()) {
	// 		$professors->the_post();
	// 		get_template_part('template-parts/content-professor');
	// 	}
	// 	wp_reset_postdata();
	// }

	function display_prof_grid()
	{
		ob_start();
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'professeur',
		);

		$profs = get_posts($args);

		if ($profs) :
		?>
			<style>
				#all-professeurs {
					/* Removed rules to use slick carousel library rules instead */
					max-width: 1364px;
					margin: 0 auto;
					display: grid;
					grid-template-columns: repeat(3, minmax(0, 1fr));
					grid-gap: 85px;
				}

				.professeur-img-grid {
					height: 365px !important;
				}
			</style>

			<div id="all-professeurs">
				<?php
				foreach ($profs as $key => $prof) :
					$isEven = $key % 2 == 0;
					$red_tranparent = !$isEven ? 'rgba(187, 35, 50, 0.5)' : 'rgba(239, 89, 80, 0.5)';
					$red = !$isEven ? 'rgba(187, 35, 50, 1)' : 'rgba(239, 89, 80, 1)';
					$social_networks = get_field('reseaux_sociaux', $prof->ID);
					$disciplines = get_field('disciplines_enseignees', $prof->ID);
				?>
					<div class="professeur">
						<a title="En savoir plus sur le professeur" href="<?php echo get_post_permalink($prof); ?>">
							<div style="background: url('<?php echo get_field('image_grille', $prof->ID); ?>');" class="professeur-img professeur-img-grid"></div>
							<div style="background-color: <?php echo $red_tranparent; ?> !important;" class="professeur-title">
								<h3><?php echo esc_html_e(get_field('prenom', $prof)); ?> <span><?php echo esc_html_e(get_field('nom', $prof)) ?></span></h3>
							</div>
							<div class="professeur-content" style="background-color: <?php echo $red; ?>">
								<div class="professeur-disciplines">
									<?php
									$i = 0;
									foreach ($disciplines as $key => $value) {
										// echo '<a href ="' . esc_html_e(get_post_permalink($value)) . '" title="Lien vers page' . esc_html_e(get_the_title($value->ID)) . ' ">' . esc_html_e(get_the_title($value->ID)) . '</a>';
									?>
										<a href="<?php echo esc_html_e(get_post_permalink($value)); ?>" title="Lien vers page <?php echo esc_html_e(get_the_title($value->ID)); ?> "><?php echo esc_html_e(get_the_title($value->ID)); ?></a>
									<?php
										if (++$i !== count($disciplines)) {
											echo "<span class='coma'>,</span>";
										}
									} ?>
								</div>
								<div class="professeur-sn">
									<?php foreach ($social_networks as $key => $value) {
										if ($value) {
											if ($key === 'facebook') echo '<a target="_blank" title="lien vers la page facebook du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
											if ($key === 'instagram') echo '<a target="_blank" title="lien vers la page instagram du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-instagram"></i></a>';
											if ($key === 'youtube') echo '<a target="_blank" title="lien vers la page youtube du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-youtube"></i></a>';
										}
									} ?>
								</div>
							</div>
						</a>
						<div class="professeur-page-link">
							<a title="En savoir plus sur le professeur" href="<?php echo get_post_permalink($prof); ?>">En savoir plus</a>
						</div>
					</div>
				<?php
				endforeach;
				?>
			</div>
		<?php
		endif;
		return ob_get_clean();
	}
	add_shortcode('profs_grid', 'display_prof_grid');

	// Shortcode to display other prof
	function display_other_prof()
	{
		ob_start();
		$current_post_id = get_the_ID();
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'professeur',
			'post__not_in' => array($current_post_id),
		);

		$profs = get_posts($args);

		if ($profs) :
		?>
			<style>
				#other-professeurs {
					/* Removed rules to use slick carousel library rules instead */
					max-width: 1620px;
					margin: 0 auto;
					/* display: grid;
				grid-template-columns: repeat(3, minmax(0, 1fr));
				grid-gap: 20px; */
				}
			</style>

			<div id="other-professeurs" class='carousel-prof'>
				<?php
				foreach ($profs as $key => $prof) :
					$isEven = $key % 2 == 0;
					$red_tranparent = $isEven ? 'rgba(187, 35, 50, 0.5)' : 'rgba(239, 89, 80, 0.5)';
					$red = $isEven ? 'rgba(187, 35, 50, 1)' : 'rgba(239, 89, 80, 1)';
					$social_networks = get_field('reseaux_sociaux', $prof->ID);
					$disciplines = get_field('disciplines_enseignees', $prof->ID);
				?>
					<div class="professeur">
						<a title="En savoir plus sur le professeur" href="<?php echo get_post_permalink($prof); ?>">
							<div style="background: url('<?php echo get_field('image_grille', $prof->ID); ?>');" class="professeur-img"></div>
							<div style="background-color: <?php echo $red_tranparent; ?> !important;" class="professeur-title">
								<h3><?php echo esc_html_e(get_field('prenom', $prof)); ?> <span><?php echo esc_html_e(get_field('nom', $prof)) ?></span></h3>
							</div>
							<div class="professeur-content" style="background-color: <?php echo $red; ?>">
								<div class="professeur-disciplines">
									<?php
									$i = 0;
									foreach ($disciplines as $key => $value) {
										// echo '<a href ="' . esc_html_e(get_post_permalink($value)) . '" title="Lien vers page' . esc_html_e(get_the_title($value->ID)) . ' ">' . esc_html_e(get_the_title($value->ID)) . '</a>';
									?>
										<a href="<?php echo esc_html_e(get_post_permalink($value)); ?>" title="Lien vers page <?php echo esc_html_e(get_the_title($value->ID)); ?> "><?php echo esc_html_e(get_the_title($value->ID)); ?></a>
									<?php
										if (++$i !== count($disciplines)) {
											echo "<span class='coma'>,</span>";
										}
									} ?>
								</div>
								<div class="professeur-sn">
									<?php foreach ($social_networks as $key => $value) {
										if ($value) {
											if ($key === 'facebook') echo '<a target="_blank" title="lien vers la page facebook du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
											if ($key === 'instagram') echo '<a target="_blank" title="lien vers la page instagram du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-instagram"></i></a>';
											if ($key === 'youtube') echo '<a target="_blank" title="lien vers la page youtube du professeur" style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-youtube"></i></a>';
										}
									} ?>
								</div>
							</div>
						</a>
						<div class="professeur-page-link">
							<a title="En savoir plus sur le professeur" href="<?php echo get_post_permalink($prof); ?>">En savoir plus</a>
						</div>
					</div>
				<?php
				endforeach;
				?>
			</div>
		<?php
		endif;
		return ob_get_clean();
	}
	add_shortcode('other_prof', 'display_other_prof');

	// Shortcode to display single prof title
	function display_prof_post_title()
	{
		ob_start();
		global $post;
		$are_multiple_prof = get_field('plusieurs_professeurs');
		$first_name = get_field('prenom', $post->ID);
		$last_name = get_field('nom', $post->ID);
		$who_is_are_string = 'Qui est';
		if ($are_multiple_prof) {
			$who_is_are_string = 'Qui sont';
		}
		?>
		<p class="prof-title">
			<?php echo $who_is_are_string; ?> <br /> <span><?php echo $first_name; ?> <?php echo $last_name; ?></span>
		</p>
		<style>
			.prof-title {
				text-align: center;
				font-size: 65px;
				text-transform: uppercase;
				line-height: 1.3em;
				font-weight: 200;
			}

			.prof-title span {
				background-color: #E75950;
				color: #fff;
				font-weight: bold;
				padding: 5px 10px;
			}
		</style>
	<?php
		return ob_get_clean();
	}
	add_shortcode('prof_post_title', 'display_prof_post_title');

	// Shortcode to display disciplines enseignees title
	function display_disciplines_enseignees_single_prof_title()
	{
		ob_start();
		global $post;
		$first_name = get_field('prenom', $post->ID);
		$last_name = get_field('nom', $post->ID);
	?>
		<h2 class="disciplines-enseignees-title"><span>Les disciplines enseignées</span> par <?php echo esc_html($first_name . ' ' . $last_name); ?></h2>
		<style>
			.disciplines-enseignees-title {
				font-size: 65px;
				text-transform: uppercase;
				font-weight: 200;
			}

			.disciplines-enseignees-title span {
				font-weight: bold;
				color: #BB2332;
			}
		</style>

		<?php
		return ob_get_clean();
	}
	add_shortcode('disciplines_enseignees_title', 'display_disciplines_enseignees_single_prof_title');

	// Shortcode to display Disciplines posts related to a Professeur post
	function display_related_disciplines_for_professeur()
	{
		ob_start();

		global $post;

		if ($post) :
			$disciplines = get_field('disciplines_enseignees');
		?>
			<div id="disciplines">
				<?php
				foreach ($disciplines as $discipline) :
					// $categories = wp_get_post_categories($discipline->ID);
					$categories = wp_get_post_tags($discipline->ID);
				?>
					<div class="discipline">
						<a href="<?php echo esc_html($discipline->guid); ?>" title="">
							<!-- Image -->
							<div style="background: url('<?php echo get_field('image_grille', $discipline->ID)['url']; ?>');" class="discipline-img"></div>
							<!-- Content -->
							<div class="discipline-content">
								<!-- Title -->
								<div class="red-low-opacity-bg">
									<h3 class="discipline-title"><?php echo esc_html($discipline->post_title); ?></h3>
								</div>
								<!-- Separator -->
								<div class="red-separator"></div>
								<!-- Categories -->
								<?php
								if ($categories) :
								?>
									<div class="red-full-opacity-bg">
										<?php
										foreach ($categories as $categoryID) :
											$category = get_category($categoryID);
											echo esc_html($category->name . ' ');
										endforeach;
										?>
									</div>
								<?php
								endif;
								?>
							</div>
						</a>
					</div>
				<?php
				endforeach;
				?>
			</div>

			<style>
				.discipline {
					text-align: center;
				}

				.discipline-content {
					margin-top: -112px;
				}

				.discipline-img {
					background-position: center !important;
					background-repeat: no-repeat !important;
					background-size: cover !important;
					height: 293px;
					max-width: 293px;
					margin: 0 auto;
				}

				#disciplines {
					display: grid;
					grid-template-columns: repeat(4, minmax(0, 1fr));
					gap: 10%;
				}

				.red-low-opacity-bg {
					background: rgb(187, 35, 50, 0.70);
					padding: 16px 0;
					height: 110px;
					max-width: 234px;
					margin: 0 auto;
					display: flex;
					justify-content: center;
					align-items: center;
				}

				.red-low-opacity-bg h3 {
					margin: 0 !important;
				}

				.red-full-opacity-bg {
					background: #BB2332;
					padding: 16px;
					max-height: 110px;
					max-width: 234px;
					margin: 0 auto;
					color: #fff;
				}

				.red-separator {
					height: 2px;
					background: #E75950;
					max-width: 293px;
					margin: 0 auto;
				}

				.discipline-title {
					color: #fff;
					text-transform: uppercase;
					text-align: center;
				}
			</style>
	<?php
		endif;

		return ob_get_clean();
	}
	add_shortcode('disciplines_enseignees', 'display_related_disciplines_for_professeur');
