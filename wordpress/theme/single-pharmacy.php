<?php
/**
 * Template for individual pharmacy location pages.
 *
 * @package 24HourPharmacy
 */

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

			</div><!-- .entry-content -->

		</article><!-- #post-## -->

		<?php endwhile; ?>

		<?php do_action( 'kadence_after_content' ); ?>

	</main><!-- #main -->

	<?php get_sidebar(); ?>

	<!-- Ad Zone: Sidebar -->
	<aside class="ad-zone-sidebar" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></aside>

</div><!-- #primary -->

<!-- Ad Zone: Footer -->
<div class="ad-zone-footer" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

<!-- Medical Disclaimer -->
<div class="medical-disclaimer" role="note">
	<p>
		<strong><?php esc_html_e( 'Medical Disclaimer:', '24hourpharmacy' ); ?></strong>
		<?php esc_html_e( 'The information on this page is for informational purposes only and does not constitute medical advice. Always consult a licensed pharmacist or healthcare provider with questions about your medications.', '24hourpharmacy' ); ?>
	</p>
</div>

<?php get_footer(); ?>
