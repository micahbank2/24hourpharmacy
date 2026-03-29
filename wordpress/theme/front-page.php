<?php
/**
 * Homepage template.
 *
 * @package 24HourPharmacy
 */

$site_name    = get_bloginfo( 'name' );
$site_url     = home_url( '/' );
$description  = get_bloginfo( 'description' ) ?: __( 'Find 24-hour pharmacies open right now near you. Search by city, ZIP code, or address across the United States.', '24hourpharmacy' );

$schema = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'WebSite',
	'name'        => $site_name,
	'url'         => $site_url,
	'description' => $description,
	'potentialAction' => array(
		'@type'       => 'SearchAction',
		'target'      => array(
			'@type'       => 'EntryPoint',
			'urlTemplate' => $site_url . '?s={search_term_string}',
		),
		'query-input' => 'required name=search_term_string',
	),
);

get_header();

// Suppress Kadence sidebar — full-width layout (D-05).
add_filter( 'kadence_display_sidebar', '__return_false' );
?>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
</script>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php do_action( 'kadence_before_content' ); ?>

		<!-- Ad Zone: Header -->
		<div class="ad-zone-header" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- FTC Affiliate Disclosure (shortcode — single source of truth in class-shortcodes.php) -->
		<?php echo do_shortcode( '[affiliate_disclosure]' ); ?>

		<!-- Hero Section (D-05): city search form — no pharmacy finder widget on homepage -->
		<section class="hero-section" aria-label="<?php esc_attr_e( 'Find a Pharmacy', '24hourpharmacy' ); ?>">
			<div class="hero-inner">
				<h1 class="hero-title"><?php esc_html_e( 'Find a 24-Hour Pharmacy Near You', '24hourpharmacy' ); ?></h1>
				<p class="hero-subtitle">
					<?php esc_html_e( 'Search pharmacies open right now across the United States.', '24hourpharmacy' ); ?>
				</p>
				<form class="city-search-form" action="" method="get" id="city-search">
					<label for="city-input" class="screen-reader-text">
						<?php esc_html_e( 'Enter city name', '24hourpharmacy' ); ?>
					</label>
					<input type="text" id="city-input" list="city-datalist"
					       placeholder="<?php esc_attr_e( 'Enter a city name...', '24hourpharmacy' ); ?>"
					       autocomplete="off"
					       aria-label="<?php esc_attr_e( 'City name', '24hourpharmacy' ); ?>">
					<datalist id="city-datalist">
						<?php
						/* Path may need adjustment based on Hostinger WP install structure. Fallback: empty datalist, form still redirects. */
						$cities_json_path = ABSPATH . '../data/cities.json';
						if ( file_exists( $cities_json_path ) ) {
							$cities_raw  = file_get_contents( $cities_json_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
							$cities_data = json_decode( $cities_raw, true );
							if ( is_array( $cities_data ) ) {
								foreach ( $cities_data as $city_item ) {
									$label = esc_attr( $city_item['name'] . ', ' . $city_item['state'] );
									echo '<option value="' . $label . '">';
								}
							}
						}
						?>
					</datalist>
					<button type="submit"><?php esc_html_e( 'Find Pharmacies', '24hourpharmacy' ); ?></button>
				</form>
			</div>
		</section><!-- .hero-section -->

		<!-- Popular Cities (D-06) -->
		<section class="popular-cities-section">
			<h2><?php esc_html_e( 'Popular Cities', '24hourpharmacy' ); ?></h2>
			<div class="popular-cities-grid">
				<?php
				$popular_cities = array(
					array( 'name' => 'New York',     'slug' => 'new-york' ),
					array( 'name' => 'Los Angeles',  'slug' => 'los-angeles' ),
					array( 'name' => 'Chicago',      'slug' => 'chicago' ),
					array( 'name' => 'Houston',      'slug' => 'houston' ),
					array( 'name' => 'Phoenix',      'slug' => 'phoenix' ),
					array( 'name' => 'Philadelphia', 'slug' => 'philadelphia' ),
					array( 'name' => 'San Antonio',  'slug' => 'san-antonio' ),
					array( 'name' => 'San Diego',    'slug' => 'san-diego' ),
					array( 'name' => 'Dallas',       'slug' => 'dallas' ),
					array( 'name' => 'San Jose',     'slug' => 'san-jose' ),
					array( 'name' => 'Austin',       'slug' => 'austin' ),
					array( 'name' => 'Miami',        'slug' => 'miami' ),
				);
				foreach ( $popular_cities as $pc ) :
				?>
				<a href="/city/<?php echo esc_attr( $pc['slug'] ); ?>/" class="popular-city-card">
					<?php echo esc_html( $pc['name'] ); ?>
				</a>
				<?php endforeach; ?>
			</div>
		</section><!-- .popular-cities-section -->

		<!-- Ad Zone: In-Content -->
		<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- How It Works (D-06) -->
		<section class="how-it-works-section">
			<h2><?php esc_html_e( 'How It Works', '24hourpharmacy' ); ?></h2>
			<div class="how-it-works-steps">
				<div class="how-it-works-step">
					<h3><?php esc_html_e( 'Search Your City', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'Enter your city name above to find nearby 24-hour pharmacies with real-time hours and directions.', '24hourpharmacy' ); ?></p>
				</div>
				<div class="how-it-works-step">
					<h3><?php esc_html_e( 'Find an Open Pharmacy', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'Browse results sorted by distance. See hours, phone numbers, and addresses for each location.', '24hourpharmacy' ); ?></p>
				</div>
				<div class="how-it-works-step">
					<h3><?php esc_html_e( 'Save on Prescriptions', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'Use a free discount card to reduce your out-of-pocket costs at any participating pharmacy.', '24hourpharmacy' ); ?></p>
				</div>
			</div>
		</section><!-- .how-it-works-section -->

		<!-- Featured Articles (D-06) -->
		<section class="featured-articles-section">
			<h2><?php esc_html_e( 'Prescription Savings Guides', '24hourpharmacy' ); ?></h2>
			<ul class="featured-articles-list">
				<li><a href="/pharmacy-savings-guide/"><?php esc_html_e( 'The Complete Guide to Saving Money at the Pharmacy', '24hourpharmacy' ); ?></a></li>
				<li><a href="/goodrx-alternatives/"><?php esc_html_e( 'Best GoodRx Alternatives for Prescription Discounts', '24hourpharmacy' ); ?></a></li>
				<li><a href="/discount-cards-explained/"><?php esc_html_e( 'How Prescription Discount Cards Work', '24hourpharmacy' ); ?></a></li>
				<li><a href="/uninsured-pharmacy-guide/"><?php esc_html_e( 'Getting Prescriptions Without Insurance: A Complete Guide', '24hourpharmacy' ); ?></a></li>
			</ul>
		</section><!-- .featured-articles-section -->

		<!-- Medical Disclaimer (shortcode — single source of truth) -->
		<?php echo do_shortcode( '[medical_disclaimer]' ); ?>

		<!-- Ad Zone: Footer -->
		<div class="ad-zone-footer" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<?php do_action( 'kadence_after_content' ); ?>

	</main><!-- #main -->

</div><!-- #primary -->

<script>
document.getElementById( 'city-search' ).addEventListener( 'submit', function( e ) {
	e.preventDefault();
	var val  = document.getElementById( 'city-input' ).value.trim().toLowerCase();
	var slug = val.split( ',' )[0].trim().replace( /\s+/g, '-' ).replace( /[^a-z0-9-]/g, '' );
	if ( slug ) {
		window.location.href = '/city/' + slug + '/';
	}
} );
</script>

<?php get_footer(); ?>
