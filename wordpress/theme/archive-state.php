<?php
/**
 * Template for state archive pages — lists cities in a state.
 *
 * @package 24HourPharmacy
 */

// Remove sidebar on state archive pages — full-width layout.
add_filter( 'kadence_display_sidebar', '__return_false' );

$queried_object = get_queried_object();
$state_name     = $queried_object ? esc_html( $queried_object->name ) : esc_html__( 'This State', '24hourpharmacy' );
$state_slug     = $queried_object ? $queried_object->slug : '';
$page_url       = get_term_link( $queried_object );
$site_name      = get_bloginfo( 'name' );

$schema = array(
	'@context'        => 'https://schema.org',
	'@type'           => 'CollectionPage',
	'name'            => sprintf( __( '24-Hour Pharmacies in %s', '24hourpharmacy' ), $state_name ),
	'description'     => sprintf(
		__( 'Find 24-hour pharmacies open right now across cities in %s. Browse by city to see local pharmacy hours, locations, and contact info.', '24hourpharmacy' ),
		$state_name
	),
	'url'             => is_wp_error( $page_url ) ? '' : $page_url,
	'publisher'       => array(
		'@type' => 'Organization',
		'name'  => $site_name,
		'url'   => home_url( '/' ),
	),
	'breadcrumb'      => array(
		'@type'           => 'BreadcrumbList',
		'itemListElement' => array(
			array(
				'@type'    => 'ListItem',
				'position' => 1,
				'name'     => 'Home',
				'item'     => home_url( '/' ),
			),
			array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => $state_name,
				'item'     => is_wp_error( $page_url ) ? '' : $page_url,
			),
		),
	),
);

get_header();
?>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
</script>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php do_action( 'kadence_before_content' ); ?>

		<!-- Ad Zone: Header -->
		<div class="ad-zone-header" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<header class="page-header">
			<h1 class="page-title">
				<?php
				printf(
					/* translators: %s: state name */
					esc_html__( '24-Hour Pharmacies in %s', '24hourpharmacy' ),
					$state_name
				);
				?>
			</h1>
			<p class="page-description">
				<?php
				printf(
					/* translators: %s: state name */
					esc_html__( 'Browse cities in %s to find 24-hour pharmacies open right now near you.', '24hourpharmacy' ),
					$state_name
				);
				?>
			</p>
		</header><!-- .page-header -->

		<?php if ( have_posts() ) : ?>

		<section class="city-list-section" aria-label="<?php esc_attr_e( 'Cities', '24hourpharmacy' ); ?>">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Cities', '24hourpharmacy' ); ?></h2>
			<ul class="city-list">
				<?php while ( have_posts() ) : the_post(); ?>
				<li class="city-list__item">
					<a href="<?php the_permalink(); ?>" class="city-list__link">
						<?php the_title(); ?>
						<?php
						$pharmacy_count = get_post_meta( get_the_ID(), '_city_pharmacy_count', true );
						if ( $pharmacy_count ) :
						?>
						<span class="city-list__count">
							<?php
							printf(
								/* translators: %d: number of pharmacies */
								esc_html( _n( '%d pharmacy', '%d pharmacies', (int) $pharmacy_count, '24hourpharmacy' ) ),
								(int) $pharmacy_count
							);
							?>
						</span>
						<?php endif; ?>
					</a>
				</li>
				<?php endwhile; ?>
			</ul><!-- .city-list -->

			<?php the_posts_pagination(); ?>
		</section><!-- .city-list-section -->

		<?php else : ?>

		<p><?php esc_html_e( 'No cities found for this state yet. Check back soon.', '24hourpharmacy' ); ?></p>

		<?php endif; ?>

		<!-- Ad Zone: In-Content -->
		<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<?php do_action( 'kadence_after_content' ); ?>

		<!-- Ad Zone: Footer -->
		<div class="ad-zone-footer" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- Medical Disclaimer (shortcode — single source of truth) -->
		<?php echo do_shortcode( '[medical_disclaimer]' ); ?>

	</main><!-- #main -->

</div><!-- #primary -->

<?php get_footer(); ?>
