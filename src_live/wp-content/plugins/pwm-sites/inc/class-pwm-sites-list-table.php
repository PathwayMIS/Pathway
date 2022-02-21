<?php

if ( ! class_exists( 'PWM_Sites_List_Table' ) ) {

	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	class PWM_Sites_List_Table extends WP_List_Table {
		private $primary_column = 'id';

		public function __construct() {
			parent::__construct( [
				'singular' => __( 'Site', 'pwm-sites' ),
				'plural'   => __( 'Sites', 'pwm-sites' ),
				'ajax'     => false
			] );
		}

		public function display() {
			$singular = $this->_args['singular'];
			$this->display_tablenav( 'top' );
			$this->screen->render_screen_reader_content( 'heading_list' ); ?>

			<table class="pwm-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
				<thead>
					<tr>
						<?php $this->print_column_headers(); ?>
					</tr>
				</thead>
				<tbody id="the-list"<?php echo( $singular ) ? " data-wp-lists='list:$singular'" : ''; ?>>
					<?php $this->display_rows_or_placeholder(); ?>
				</tbody>
				<tfoot>
					<tr>
						<?php $this->print_column_headers( false ); ?>
					</tr>
				</tfoot>
			</table>
			<?php $this->display_tablenav( 'bottom' );
		}

		public function get_columns(){
			$columns = array(
				//'cb'		=> __( 'ID', 'pwm-sites' ),
				'id'		=> __( 'ID', 'pwm-sites' ),
				'subdomain'	=> __( 'Subdomain', 'pwm-sites' ),
				'email'		=> __( 'Email', 'pwm-sites' ),
				'submitted'	=> __( 'Submitted', 'pwm-sites' ),
				'installed'	=> __( 'Installed', 'pwm-sites' ),
				'control'	=> ''
			);

			return $columns;
		}

		public function column_default( $item, $column_name ) {
			switch( $column_name ) {
				//case 'cb':
				case 'id':
				case 'subdomain':
				case 'email':
				case 'submitted':
				case 'installed':
				case 'control':
					return $item[ $column_name ];
			default:
				return print_r( $item, true );
			}
		}

		public function get_sortable_columns() {
			$sortable_columns = array(
				'subdomain'		=> array( 'subdomain', true ),
				'email'			=> array( 'email', true ),
				'submitted'		=> array( 'submitted', true ),
				'installed'		=> array( 'installed', true ),
			);

			return $sortable_columns;
		}


		public static function get_sites() {
			global $wpdb;

			$result = $wpdb->get_results( "SELECT `id`, `subdomain`, `email`, `date_submitted` FROM {$wpdb->base_prefix}pwm_sites ORDER BY `id` DESC", ARRAY_A );

			return $result;
		}

		public static function get_status( $data = array() ) {
			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-site-builder.php' );

			foreach ( $data as $key => $value ) {
				$data[ $key ]['installed'] = ( PWM_Site_Builder::is_installed( $value['subdomain'] ) ) ? __( 'Yes', 'pwm-sites' ) : __( 'No', 'pwm-sites' );
			}

			return $data;
		}

		public function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : $this->primary_column;
			$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';
			$result = strnatcasecmp( $a[$orderby], $b[$orderby] );
			return ( $order === 'asc' ) ? $result : -$result;
		}

		public function prepare_items() {
			$columns = $this->get_columns();
			$hidden = array( 'id' );
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable, $this->primary_column );

			$data = self::get_sites();

			usort( $data, array( &$this, 'usort_reorder' ) );

			$per_page = 100;
			$current_page = $this->get_pagenum();
			$total_items = count( $data );

			$found_data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page
			) );

			$found_data = self::get_status( $found_data );

			$this->items = $found_data;
		}

		// public function column_cb( $item ) {
		// 	return sprintf( '<input class="pwm-site-id" type="checkbox" name="pwm_site_id[]" value="%s">', $item['id'] );
		// }

		public function column_subdomain( $item ) {
			return ( ! empty( $item['subdomain'] ) ) ? $item['subdomain'] : '-';
		}

		public function column_email( $item ) {
			return ( ! empty( $item['email'] ) ) ? $item['email'] : '-';
		}

		public function column_submitted( $item ) {
			return date_i18n( get_option( 'date_format' ), strtotime( $item['date_submitted'] ) );
		}

		public function column_control( $item ) {
			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-site-builder.php' );

			$button = sprintf( '<a class="pwm-button pwm-button-primary pwm-button-table-action" href="%s">%s</a>', admin_url( 'admin.php?page=pwm_site&site_action=manage&site_id=' . $item['id'] ), __( 'Manage', 'pwm-sites' ) );

			if ( PWM_Site_Builder::is_installed( $item['subdomain'] ) )
				$button .= sprintf( ' <a class="pwm-button pwm-button-primary pwm-button-table-action" href="%s" target="_blank">%s</a>', esc_url( PWM_Site_Builder::get_site_url( $item['subdomain'] ) ), __( 'Open', 'pwm-sites' ) );

			$button .= sprintf( '<a class="pwm-button pwm-button-primary pwm-button-table-action" href="%s">%s</a>', admin_url( 'admin.php?page=pwm_sites&site_action=delete&site_id=' . $item['id'] ), __( 'Delete', 'pwm-sites' ) );

			return $button;
		}

		public function no_items() {
			_e( 'No sites found.', 'pwm-sites' );
		}
	}
}