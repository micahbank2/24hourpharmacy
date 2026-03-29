<?php
/**
 * Template Name: Savings Hub
 *
 * Prescription savings hub — discount cards, coupons, and affiliate offers.
 * All affiliate links are managed through ThirstyAffiliates (/go/ URLs).
 *
 * @package 24HourPharmacy
 */

$page_title  = get_the_title();
$page_url    = get_permalink();
$site_name   = get_bloginfo( 'name' );
$description = __( 'Free prescription discount cards that can save you up to 80% on medications at major pharmacies. No sign-up required.', '24hourpharmacy' );

$schema = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'WebPage',
	'name'        => $page_title,
	'description' => $description,
	'url'         => $page_url,
	'publisher'   => array(
		'@type' => 'Organization',
		'name'  => $site_name,
		'url'   => home_url( '/' ),
	),
	'breadcrumb'  => array(
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
				'name'     => $page_title,
				'item'     => $page_url,
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

		<!-- FTC Affiliate Disclosure — required above fold on affiliate pages -->
		<div class="ftc-disclosure ftc-disclosure--prominent">
			<p>
				<strong><?php esc_html_e( 'Affiliate Disclosure:', '24hourpharmacy' ); ?></strong>
				<?php esc_html_e( 'This page contains affiliate links. If you activate a discount card or make a purchase through our links, we may earn a commission at no extra cost to you. We only recommend programs we have researched and believe provide genuine value.', '24hourpharmacy' ); ?>
			</p>
		</div>

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'savings-page' ); ?>>

			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<p class="page-description"><?php echo esc_html( $description ); ?></p>
			</header><!-- .entry-header -->

			<div class="entry-content">

				<!-- Discount Card Widget (async React bundle via shortcode) -->
				<section class="discount-card-section" aria-label="<?php esc_attr_e( 'Prescription Discount Card', '24hourpharmacy' ); ?>">
					<h2><?php esc_html_e( 'Get Your Free Prescription Discount Card', '24hourpharmacy' ); ?></h2>
					<p><?php esc_html_e( 'Print, text, or email your free discount card. Show it at the pharmacy counter — no insurance required.', '24hourpharmacy' ); ?></p>
					<?php echo do_shortcode( '[discount_card]' ); ?>
				</section>

				<!-- Ad Zone: In-Content -->
				<div class="ad-zone-in-content" aria-label="<?php esc_attr_e( 'Advertisement', '24hourpharmacy' ); ?>"></div>

				<!-- How it works section -->
				<section class="how-it-works-section">
					<h2><?php esc_html_e( 'How Prescription Discount Cards Work', '24hourpharmacy' ); ?></h2>
					<ol class="steps-list">
						<li><?php esc_html_e( 'Get your free discount card above — no sign-up or personal information required.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Take the card (printed, on your phone, or texted to you) to any participating pharmacy.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Show the card to the pharmacist before they process your prescription.', '24hourpharmacy' ); ?></li>
						<li><?php esc_html_e( 'Pay the discounted price — often far below the retail cash price.', '24hourpharmacy' ); ?></li>
					</ol>
					<p>
						<strong><?php esc_html_e( 'Important:', '24hourpharmacy' ); ?></strong>
						<?php esc_html_e( 'You cannot use a discount card and insurance at the same time. Compare prices to decide which saves you more.', '24hourpharmacy' ); ?>
					</p>
				</section>

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
		<?php esc_html_e( 'The information on this page is for informational purposes only and does not constitute medical advice. Consult a licensed healthcare professional before making any decisions about your medications.', '24hourpharmacy' ); ?>
	</p>
</div>

<?php get_footer(); ?>
