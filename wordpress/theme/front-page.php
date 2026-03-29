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
?>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
</script>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php do_action( 'kadence_before_content' ); ?>

		<!-- Ad Zone: Header -->
		<div class="ad-zone-header" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- FTC Affiliate Disclosure -->
		<div class="ftc-disclosure">
			<p><?php esc_html_e( 'Disclosure: This site contains affiliate links. We may earn a commission when you use a discount card or make a purchase through our links, at no extra cost to you.', '24hourpharmacy' ); ?></p>
		</div>

		<!-- Hero Section -->
		<section class="hero-section" aria-label="<?php esc_attr_e( 'Site Hero', '24hourpharmacy' ); ?>">
			<div class="hero-inner">
				<h1 class="hero-title"><?php esc_html_e( 'Find a 24-Hour Pharmacy Near You', '24hourpharmacy' ); ?></h1>
				<p class="hero-subtitle">
					<?php esc_html_e( 'Search pharmacies open right now across the United States. Updated daily with real hours.', '24hourpharmacy' ); ?>
				</p>

				<!-- Pharmacy Finder Widget (async React bundle via shortcode) -->
				<div class="hero-finder">
					<?php echo do_shortcode( '[pharmacy_finder]' ); ?>
				</div>
			</div>
		</section><!-- .hero-section -->

		<!-- Ad Zone: In-Content -->
		<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- Homepage body content (set in WP editor) -->
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>

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
		<?php esc_html_e( 'The information on this site is for informational purposes only and does not constitute medical advice. Always consult a licensed healthcare professional.', '24hourpharmacy' ); ?>
	</p>
</div>

<?php get_footer(); ?>
