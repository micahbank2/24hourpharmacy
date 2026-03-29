<?php
/**
 * Template for individual city pharmacy finder pages.
 *
 * @package 24HourPharmacy
 */

// Suppress Kadence sidebar injection — full-width single-column layout.
add_filter( 'kadence_display_sidebar', '__return_false' );

// Gather meta for structured data and content.
$city_name    = get_the_title();
$state_terms  = get_the_terms( get_the_ID(), 'state' );
$state_name   = ( $state_terms && ! is_wp_error( $state_terms ) ) ? esc_html( $state_terms[0]->name ) : '';
$page_url     = get_permalink();
$site_name    = get_bloginfo( 'name' );
$description  = get_post_meta( get_the_ID(), '_city_meta_description', true );
if ( ! $description ) {
	/* translators: 1: city name, 2: state name */
	$description = sprintf(
		__( 'Find 24-hour pharmacies open right now in %1$s, %2$s. Search by location, hours, and services. Includes CVS, Walgreens, Walmart, and independent pharmacies.', '24hourpharmacy' ),
		$city_name,
		$state_name
	);
}

// FAQ items for structured data.
$faqs = array(
	array(
		'question' => sprintf( __( 'Are there 24-hour pharmacies in %s?', '24hourpharmacy' ), $city_name ),
		'answer'   => sprintf( __( 'Yes. Several pharmacy chains including CVS, Walgreens, and Walmart operate 24-hour locations in %s. Use the finder above to locate the nearest open pharmacy.', '24hourpharmacy' ), $city_name ),
	),
	array(
		'question' => __( 'What pharmacies are open overnight?', '24hourpharmacy' ),
		'answer'   => __( 'CVS Pharmacy, Walgreens, and select Walmart Pharmacy locations commonly operate 24 hours or maintain extended overnight hours. Availability varies by location.', '24hourpharmacy' ),
	),
	array(
		'question' => __( 'Can I fill a prescription at a 24-hour pharmacy?', '24hourpharmacy' ),
		'answer'   => __( 'Yes. A licensed pharmacist is on duty at all times at 24-hour locations and can fill both new and refill prescriptions at any hour.', '24hourpharmacy' ),
	),
	array(
		'question' => __( 'How can I save money on prescriptions?', '24hourpharmacy' ),
		'answer'   => __( 'Free prescription discount cards such as GoodRx, SingleCare, and RxSaver can significantly reduce out-of-pocket costs at most major pharmacy chains.', '24hourpharmacy' ),
	),
);

// Build FAQ structured data array.
$faq_entities = array();
foreach ( $faqs as $faq ) {
	$faq_entities[] = array(
		'@type'          => 'Question',
		'name'           => $faq['question'],
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $faq['answer'],
		),
	);
}

$structured_data = array(
	array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebPage',
		'name'            => $city_name . ' 24-Hour Pharmacies',
		'description'     => $description,
		'url'             => $page_url,
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
					'item'     => get_term_link( $state_terms[0] ?? '', 'state' ),
				),
				array(
					'@type'    => 'ListItem',
					'position' => 3,
					'name'     => $city_name,
					'item'     => $page_url,
				),
			),
		),
	),
	array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_entities,
	),
);

get_header();
?>

<?php foreach ( $structured_data as $schema ) : ?>
<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
</script>
<?php endforeach; ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php do_action( 'kadence_before_content' ); ?>

		<!-- Ad Zone: Header -->
		<div class="ad-zone-header" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

		<!-- FTC Affiliate Disclosure (via shortcode — class-shortcodes.php) -->
		<?php echo do_shortcode( '[affiliate_disclosure]' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'city-page' ); ?>>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
					/* translators: %s: city and state name */
					printf(
						esc_html__( '24-Hour Pharmacies in %s', '24hourpharmacy' ),
						esc_html( $city_name ) . ( $state_name ? ', ' . esc_html( $state_name ) : '' )
					);
					?>
				</h1>
				<p class="city-intro">
					<?php echo esc_html( $description ); ?>
				</p>
			</header><!-- .entry-header -->

			<div class="entry-content">

				<!-- Pharmacy Finder Widget — hero element (async React bundle via shortcode) -->
				<section class="pharmacy-finder-section" aria-label="<?php esc_attr_e( 'Pharmacy Finder', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Find a Pharmacy Open Now', '24hourpharmacy' ); ?></h2>
					<?php echo do_shortcode( '[pharmacy_finder]' ); ?>
				</section>

				<!-- Ad Zone: In-Content -->
				<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

				<!-- City editorial content -->
				<section class="city-content-section">
					<h2>
						<?php
						printf(
							/* translators: %s: city name */
							esc_html__( 'About Pharmacies in %s', '24hourpharmacy' ),
							esc_html( $city_name )
						);
						?>
					</h2>

					<?php
					// Output the post body — populated by the Python page generator.
					the_content();
					?>

					<?php if ( ! get_the_content() ) : ?>
					<!-- Placeholder body — replace via WP editor or generate-city-pages.py -->
					<p>
						<?php
						printf(
							/* translators: 1: city name, 2: state name */
							esc_html__( '%1$s, %2$s is home to several pharmacy chains that maintain 24-hour or extended-hour locations. Whether you need a late-night prescription fill, over-the-counter medications, or emergency health supplies, the pharmacies listed above can help at any hour.', '24hourpharmacy' ),
							esc_html( $city_name ),
							esc_html( $state_name )
						);
						?>
					</p>

					<h3><?php esc_html_e( 'Major Pharmacy Chains', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'The following national pharmacy chains commonly operate 24-hour or extended-hour locations in larger metro areas:', '24hourpharmacy' ); ?></p>
					<ul>
						<li><strong>CVS Pharmacy</strong> — <?php esc_html_e( 'Select CVS locations are open 24 hours. The CVS Pharmacy app shows real-time hours for each store.', '24hourpharmacy' ); ?></li>
						<li><strong>Walgreens</strong> — <?php esc_html_e( 'Many Walgreens locations maintain 24-hour pharmacy windows separate from retail store hours.', '24hourpharmacy' ); ?></li>
						<li><strong>Walmart Pharmacy</strong> — <?php esc_html_e( 'Walmart Supercenter pharmacies in high-traffic areas often operate overnight hours.', '24hourpharmacy' ); ?></li>
						<li><strong>Rite Aid</strong> — <?php esc_html_e( 'Selected Rite Aid stores offer 24-hour service depending on location.', '24hourpharmacy' ); ?></li>
					</ul>

					<h3><?php esc_html_e( 'Independent and Specialty Pharmacies', '24hourpharmacy' ); ?></h3>
					<p>
						<?php
						printf(
							/* translators: %s: city name */
							esc_html__( 'In addition to national chains, %s has independent pharmacies that may offer compounding services, specialty medications, or bilingual staff. Use the finder above to see all locations.', '24hourpharmacy' ),
							esc_html( $city_name )
						);
						?>
					</p>

					<h3><?php esc_html_e( 'How to Save on Prescriptions', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'Even without insurance, you can reduce prescription costs using free discount cards:', '24hourpharmacy' ); ?></p>
					<ul>
						<li><?php esc_html_e( 'GoodRx — accepted at most major chains nationwide', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'SingleCare — often has lower prices than GoodRx on certain generics', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'RxSaver — good for comparing prices across local pharmacies', '24hourpharmacy' ); ?></li>
					</ul>
					<p><?php esc_html_e( 'Show the discount card barcode to the pharmacist before they process your prescription. You cannot stack these cards with insurance.', '24hourpharmacy' ); ?></p>

					<h3><?php esc_html_e( 'What to Do in a Pharmacy Emergency', '24hourpharmacy' ); ?></h3>
					<p><?php esc_html_e( 'If you need medication urgently at night, here are the steps:', '24hourpharmacy' ); ?></p>
					<ol>
						<li><?php esc_html_e( 'Use the finder above to locate the nearest 24-hour pharmacy.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Call ahead to confirm they have your medication in stock.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Bring a valid government-issued ID for controlled substances.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Ask the pharmacist about generic alternatives if cost is a concern.', '24hourpharmacy' ); ?></li>
					</ol>

					<h3><?php esc_html_e( 'Frequently Asked Questions', '24hourpharmacy' ); ?></h3>
					<div class="faq-section">
						<?php foreach ( $faqs as $faq ) : ?>
						<div class="faq-item">
							<h4><?php echo esc_html( $faq['question'] ); ?></h4>
							<p><?php echo esc_html( $faq['answer'] ); ?></p>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

				</section><!-- .city-content-section -->

				<!-- Discount Card Widget -->
				<section class="discount-card-section" aria-label="<?php esc_attr_e( 'Prescription Discount Card', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Save on Prescriptions', '24hourpharmacy' ); ?></h2>
					<?php echo do_shortcode( '[discount_card]' ); ?>
				</section>

				<!-- Affiliate CTA Cards (D-04) -->
				<section class="affiliate-cta-section" aria-label="<?php esc_attr_e( 'Save on Prescriptions', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Prescription Savings Options', '24hourpharmacy' ); ?></h2>
					<div class="affiliate-cta-cards">
						<div class="affiliate-cta-card">
							<p class="affiliate-cta-card__name"><?php esc_html_e( 'GoodRx', '24hourpharmacy' ); ?></p>
							<p class="affiliate-cta-card__copy"><?php esc_html_e( 'Free prescription discount card accepted at 70,000+ pharmacies nationwide.', '24hourpharmacy' ); ?></p>
							<a href="/go/goodrx/" class="affiliate-cta-card__btn" rel="sponsored noopener" target="_blank">
								<?php esc_html_e( 'Get Free Card', '24hourpharmacy' ); ?>
							</a>
						</div>
						<div class="affiliate-cta-card">
							<p class="affiliate-cta-card__name"><?php esc_html_e( 'SingleCare', '24hourpharmacy' ); ?></p>
							<p class="affiliate-cta-card__copy"><?php esc_html_e( 'Compare prices and save up to 80% on prescription medications.', '24hourpharmacy' ); ?></p>
							<a href="/go/singlecare/" class="affiliate-cta-card__btn" rel="sponsored noopener" target="_blank">
								<?php esc_html_e( 'Compare Prices', '24hourpharmacy' ); ?>
							</a>
						</div>
						<div class="affiliate-cta-card">
							<p class="affiliate-cta-card__name"><?php esc_html_e( 'Amazon Pharmacy', '24hourpharmacy' ); ?></p>
							<p class="affiliate-cta-card__copy"><?php esc_html_e( 'Transparent pricing and free delivery on prescription medications.', '24hourpharmacy' ); ?></p>
							<a href="/go/amazon-pharmacy/" class="affiliate-cta-card__btn" rel="sponsored noopener" target="_blank">
								<?php esc_html_e( 'Shop Now', '24hourpharmacy' ); ?>
							</a>
						</div>
					</div>
				</section>

				<!-- Medical Disclaimer (via shortcode — class-shortcodes.php) -->
				<?php echo do_shortcode( '[medical_disclaimer]' ); ?>

			</div><!-- .entry-content -->

		</article><!-- #post-## -->

		<?php endwhile; ?>

		<!-- Ad Zone: Footer -->
		<div class="ad-zone-footer" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

	</main><!-- #main -->

</div><!-- #primary -->

<?php get_footer(); ?>
