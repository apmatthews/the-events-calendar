<?php
/**
 * View: Read More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/components/read-more.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version TBD
 *
 * @var string $permalink The permalink.
 */
?>
<span class="tribe-events-c-read-more-hellip"> &hellip; </span>
<div class="tribe-events-c-small-cta tribe-common-b3 tribe-events-c-read-more">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt"
	><?php esc_html_e( 'Continue Reading', 'the-events-calendar' ); ?>
	</a>
</div>
