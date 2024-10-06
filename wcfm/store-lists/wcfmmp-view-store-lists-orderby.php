<?php
/**
 * The Template for displaying store sidebar category.
 *
 * @package WCfM Markeplace Views Store Order BY
 *
 * For edit coping this to yourtheme/wcfm/store-lists
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$args = array(
	  'user_count'      => $user_count,
		'stores'          => $stores,
		'per_row'         => $per_row,
		'limit'           => $limit,
		'offset'          => $offset,
		'paged'           => $paged,
		'filter'          => $filter,
		'search'          => $search,
		'category'        => $category,
		'country'         => $country,
		'state'           => $state,
		'search_query'    => $search_query,
		'search_category' => $search_category,
		'pagination_base' => $pagination_base,
		'num_of_pages'    => $num_of_pages,
		'orderby'         => $orderby,
		'has_product'     => $has_product,
		'search_data'     => $search_data
);

if( isset( $_GET['orderby'] ) ) { $orderby = sanitize_sql_orderby($_GET['orderby']); }

?>

<div class="wcfmmp-store-lists-sorting">
  <form class="wcfm-woocommerce-ordering" action="" method="get">
		<select id="wcfmmp_store_orderby" name="orderby" class="orderby">
			<option value="newness_asc" <?php selected( $orderby, 'newness_asc' ); ?>><?php _e( 'Sort by newness: old to new', 'matico' ); ?></option>
			<option value="newness_desc" <?php selected( $orderby, 'newness_desc' ); ?>><?php _e( 'Sort by newness: new to old', 'matico' ); ?></option>
			<option value="rating_asc" <?php selected( $orderby, 'rating_asc' ); ?>><?php _e( 'Sort by average rating: low to high', 'matico' ); ?></option>
			<option value="rating_desc" <?php selected( $orderby, 'rating_desc' ); ?>><?php _e( 'Sort by average rating: high to low', 'matico' ); ?></option>
			<option value="alphabetical_asc" <?php selected( $orderby, 'alphabetical_asc' ); ?>><?php _e( 'Sort Alphabetical: A to Z', 'matico' ); ?></option>
			<option value="alphabetical_desc" <?php selected( $orderby, 'alphabetical_desc' ); ?>><?php _e( 'Sort Alphabetical: Z to A', 'matico' ); ?></option>
		</select>
		
		<?php
		if( !empty( $search_data ) ) {
			foreach( $search_data as $search_key => $search_value ) {
				if( in_array( $search_key, array( 'search_term', 'wcfmmp_store_search', 'wcfmmp_store_category', 'pagination_base', 'wcfm_paged', 'paged', 'per_row', 'per_page', 'excludes', 'orderby', 'has_product', 'nonce' ) ) ) continue;
				echo '<input type="hidden" name="'.esc_attr($search_key).'" value="'.esc_attr($search_value).'" />';
			}
		}
		?>
		<input type="hidden" name="wcfmmp_store_search" value="<?php echo esc_html($search_query); ?>" />
		<input type="hidden" name="wcfmmp_store_category" value="<?php echo esc_attr($search_category); ?>" />
		<input type="hidden" name="wcfmsc_store_categories" value="<?php echo isset( $search_data['wcfmsc_store_categories'] ) ? esc_attr($search_data['wcfmsc_store_categories']) : ''; ?>" />
		<input type="hidden" name="paged" value="1">
	</form>
	<p class="woocommerce-result-count">
		<?php printf( __( 'Showing %s–%s of %s results', 'matico' ), ($offset+1), ( $offset + count($stores) ), $user_count ); ?>
	</p>
	
	<div class="spacer"></div>
</div>