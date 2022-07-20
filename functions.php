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
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);

// Shortcode to display other prof
function display_other_prof()
{
	ob_start();
	$args = array(
		'numberposts' => -1,
		'post_type'   => 'professeur'
	);

	$profs = get_posts($args);

	if ($profs) :
?>
		<style>
			#other-professeurs {
				display: grid;
				grid-template-columns: repeat(3, minmax(0, 1fr));
				grid-gap: 20px;
			}

			.professeur-img {
				height: 365px;
				max-width: 398px !important;
				margin: 0 auto;
				background-position: center !important;
				background-size: cover !important;
				background-repeat: no-repeat !important;
				box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
			}

			.professeur-title {
				max-width: 236px !important;
				margin: 0 auto;
				margin-top: -139px;
				height: 139px;
				display: flex;
				align-items: center;
				justify-content: center;

			}

			.professeur-title h3 {
				text-align: center;
				color: #fff;
				font-size: 35px;
			}

			.professeur-title span {
				display: block;
			}

			.professeur-content {
				max-width: 398px !important;
				margin: 0 auto;
				text-align: center;
				color: #fff;
				padding-bottom: 80px;
				box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
			}

			.professeur-sn {
				display: flex;
				justify-content: center;
				align-items: center;
				margin-top: 20px;
			}

			.professeur-sn a {
				/* color: #BB2332; */
				text-decoration: none;
				font-size: 20px;
				margin-right: 20px;
				border-radius: 100%;
				background-color: #fff;
				width: 40px;
				height: 40px;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.professeur-page-link {
				max-width: 398px !important;
				margin: 0 auto;
				text-align: center;
				height: auto;
				display: flex;
				justify-content: center;
				align-items: center;
				margin-top: -30px;
			}

			.professeur-page-link a {
				background: #000 !important;
				text-align: center !important;
				color: #fff !important;
				text-transform: uppercase !important;
				padding: 18px 34px !important;
				font-size: 15px !important;
			}
		</style>

		<div id="other-professeurs">
			<?php
			foreach ($profs as $key => $prof) :
				$isEven = $key % 2 == 0;
				$red_tranparent = $isEven ? 'rgba(187, 35, 50, 0.5)' : 'rgba(239, 89, 80, 0.5)';
				$red = $isEven ? 'rgba(187, 35, 50, 1)' : 'rgba(239, 89, 80, 1)';
				$social_networks = get_field('reseaux_sociaux');
			?>
				<div class="professeur">
					<div style="background: url('<?php echo get_field('image_grille', $prof->ID); ?>');" class="professeur-img"></div>
					<div style="background-color: <?php echo $red_tranparent; ?> !important;" class="professeur-title">
						<h3><?php echo esc_html_e(get_field('prenom', $prof)); ?> <span><?php echo esc_html_e(get_field('nom', $prof)) ?></span></h3>
					</div>
					<div class="professeur-content" style="background-color: <?php echo $red; ?>">
						<span>HIP-HOP, Ragga Dance Hall</span>
						<div class="professeur-sn">
							<?php foreach ($social_networks as $key => $value) {
								if ($value) {
									if ($key === 'facebook') echo '<a style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
									if ($key === 'instagram') echo '<a style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-instagram"></i></a>';
									if ($key === 'youtube') echo '<a style="color:' . $red . ';" href="' . $value . '" target="_blank"><i class="fab fa-youtube"></i></a>';
								}
							} ?>
						</div>
					</div>
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
	<h2 class="disciplines-enseignees-title"><span>Les desiciplines enseignées</span> par <?php echo esc_html($first_name . ' ' . $last_name); ?></h2>
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
				$categories = wp_get_post_categories($discipline->ID);
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
