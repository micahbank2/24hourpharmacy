<?php
/**
 * Template for individual pharmacy location pages.
 *
 * @package 24HourPharmacy
 */

// Remove sidebar on pharmacy pages — full-width layout.
add_filter( 'kadence_display_sidebar', '__return_false' );

// Pharmacy meta fields (set by generate-city-pages.py or manually via WP admin).
$pharmacy_name    = get_the_title();
$address          = get_post_meta( get_the_ID(), '_pharmacy_address', true );
$city             = get_post_meta( get_the_ID(), '_pharmacy_city', true );
$state            = get_post_meta( get_the_ID(), '_pharmacy_state', true );
$zip              = get_post_meta( get_the_ID(), '_pharmacy_zip', true );
$phone            = get_post_meta( get_the_ID(), '_pharmacy_phone', true );
$hours_mon_fri    = get_post_meta( get_the_ID(), '_pharmacy_hours_mon_fri', true );
$hours_sat        = get_post_meta( get_the_ID(), '_pharmacy_hours_sat', true );
$hours_sun        = get_post_meta( get_the_ID(), '_pharmacy_hours_sun', true );
$is_24hr          = get_post_meta( get_the_ID(), '_pharmacy_is_24hr', true );
$latitude         = get_post_meta( get_the_ID(), '_pharmacy_latitude', true );
$longitude        = get_post_meta( get_the_ID(), '_pharmacy_longitude', true );
$website          = get_post_meta( get_the_ID(), '_pharmacy_website', true );
$page_url         = get_permalink();
$site_name        = get_bloginfo( 'name' );

// Build opening hours specification for schema.
$opening_hours_spec = array();
if ( $is_24hr ) {
	$opening_hours_spec[] = array(
		'@type'     => 'OpeningHoursSpecification',
		'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
		'opens'     => '00:00',
		'closes'    => '23:59',
	);
} else {
	if ( $hours_mon_fri ) {
		$opening_hours_spec[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
			'opens'     => substr( $hours_mon_fri, 0, 5 ),
			'closes'    => substr( $hours_mon_fri, 6, 5 ),
		);
	}
	if ( $hours_sat ) {
		$opening_hours_spec[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Saturday' ),
			'opens'     => substr( $hours_sat, 0, 5 ),
			'closes'    => substr( $hours_sat, 6, 5 ),
		);
	}
	if ( $hours_sun ) {
		$opening_hours_spec[] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Sunday' ),
			'opens'     => substr( $hours_sun, 0, 5 ),
			'closes'    => substr( $hours_sun, 6, 5 ),
		);
	}
}

// LocalBusiness / Pharmacy schema.
$schema = array(
	'@context'              => 'https://schema.org',
	'@type'                 => array( 'Pharmacy', 'LocalBusiness' ),
	'name'                  => $pharmacy_name,
	'url'                   => $page_url,
	'telephone'             => $phone ?: '',
	'address'               => array(
		'@type'           => 'PostalAddress',
		'streetAddress'   => $address ?: '',
		'addressLocality' => $city ?: '',
		'addressRegion'   => $state ?: '',
		'postalCode'      => $zip ?: '',
		'addressCountry'  => 'US',
	),
);

if ( $latitude && $longitude ) {
	$schema['geo'] = array(
		'@type'     => 'GeoCoordinates',
		'latitude'  => (float) $latitude,
		'longitude' => (float) $longitude,
	);
}

if ( ! empty( $opening_hours_spec ) ) {
	$schema['openingHoursSpecification'] = $opening_hours_spec;
}

if ( $website ) {
	$schema['sameAs'] = $website;
}

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

		<!-- FTC Affiliate Disclosure (shortcode — pharmacy pages may link to affiliate discount cards) -->
		<?php echo do_shortcode( '[affiliate_disclosure]' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'pharmacy-page' ); ?>>

			<header class="entry-header">
				<h1 class="entry-title"><?php echo esc_html( $pharmacy_name ); ?></h1>

				<?php if ( $is_24hr ) : ?>
				<span class="hours-badge hours-badge--24hr" aria-label="<?php esc_attr_e( 'Open 24 hours', '24hourpharmacy' ); ?>">
					<?php esc_html_e( 'Open 24 Hours', '24hourpharmacy' ); ?>
				</span>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-content">

				<!-- Pharmacy details card -->
				<section class="pharmacy-details-card" aria-label="<?php esc_attr_e( 'Pharmacy Details', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Location &amp; Contact', '24hourpharmacy' ); ?></h2>

					<?php if ( $address ) : ?>
					<address class="pharmacy-address">
						<p>
							<?php echo esc_html( $address ); ?><br>
							<?php echo esc_html( $city ); ?>, <?php echo esc_html( $state ); ?> <?php echo esc_html( $zip ); ?>
						</p>
						<?php if ( $phone ) : ?>
						<p>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
								<?php echo esc_html( $phone ); ?>
							</a>
						</p>
						<?php endif; ?>
					</address>
					<?php endif; ?>

					<?php if ( $website ) : ?>
					<p>
						<a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Visit Official Website', '24hourpharmacy' ); ?>
						</a>
					</p>
					<?php endif; ?>
				</section><!-- .pharmacy-details-card -->

				<!-- Hours -->
				<section class="pharmacy-hours-section">
					<h2><?php esc_html_e( 'Pharmacy Hours', '24hourpharmacy' ); ?></h2>

					<?php if ( $is_24hr ) : ?>
					<p class="hours-24hr"><?php esc_html_e( 'This pharmacy is open 24 hours a day, 7 days a week.', '24hourpharmacy' ); ?></p>
					<?php else : ?>
					<table class="pharmacy-hours-table">
						<caption class="screen-reader-text"><?php esc_html_e( 'Pharmacy Hours of Operation', '24hourpharmacy' ); ?></caption>
						<tbody>
							<tr>
								<th scope="row"><?php esc_html_e( 'Monday – Friday', '24hourpharmacy' ); ?></th>
								<td><?php echo $hours_mon_fri ? esc_html( $hours_mon_fri ) : esc_html__( 'Call to confirm', '24hourpharmacy' ); ?></td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Saturday', '24hourpharmacy' ); ?></th>
								<td><?php echo $hours_sat ? esc_html( $hours_sat ) : esc_html__( 'Call to confirm', '24hourpharmacy' ); ?></td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Sunday', '24hourpharmacy' ); ?></th>
								<td><?php echo $hours_sun ? esc_html( $hours_sun ) : esc_html__( 'Call to confirm', '24hourpharmacy' ); ?></td>
							</tr>
						</tbody>
					</table>
					<?php endif; ?>

					<p class="hours-note">
						<?php esc_html_e( 'Hours are subject to change. Always call ahead to confirm, especially on holidays.', '24hourpharmacy' ); ?>
					</p>
				</section><!-- .pharmacy-hours-section -->

				<!-- Ad Zone: In-Content -->
				<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

				<!-- Post body content (set in WP editor) -->
				<?php the_content(); ?>

				<!-- Nearby Cities (D-08): links to same-state city pages for internal linking -->
				<section class="nearby-cities-section" aria-label="<?php esc_attr_e( 'Nearby Cities', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Find More 24-Hour Pharmacies Nearby', '24hourpharmacy' ); ?></h2>
					<?php
					$pharmacy_state_name = get_post_meta( get_the_ID(), '_pharmacy_state', true );
					if ( $pharmacy_state_name ) {
						$state_term = get_term_by( 'name', $pharmacy_state_name, 'state' );
						if ( $state_term && ! is_wp_error( $state_term ) ) {
							$nearby_cities = new WP_Query( array(
								'post_type'      => 'city',
								'posts_per_page' => 6,
								'orderby'        => 'title',
								'order'          => 'ASC',
								'tax_query'      => array(
									array(
										'taxonomy' => 'state',
										'field'    => 'term_id',
										'terms'    => $state_term->term_id,
									),
								),
							) );
							if ( $nearby_cities->have_posts() ) {
								echo '<ul class="city-list">';
								while ( $nearby_cities->have_posts() ) {
									$nearby_cities->the_post();
									echo '<li class="city-list__item"><a href="' . esc_url( get_permalink() ) . '" class="city-list__link">' . esc_html( get_the_title() ) . '</a></li>';
								}
								echo '</ul>';
								wp_reset_postdata();
							} else {
								echo '<p>' . esc_html__( 'More city pages coming soon.', '24hourpharmacy' ) . '</p>';
							}
						}
					}
					?>
				</section><!-- .nearby-cities-section -->

			</div><!-- .entry-content -->

		</article><!-- #post-## -->

		<?php endwhile; ?>

		<?php do_action( 'kadence_after_content' ); ?>

		<!-- Ad Zone: Footer -->
		<div class="ad-zone-footer" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- Medical Disclaimer (shortcode — single source of truth) -->
		<?php echo do_shortcode( '[medical_disclaimer]' ); ?>

	</main><!-- #main -->

</div><!-- #primary -->

<?php get_footer(); ?>
