<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Ordoro_Resource_Product extends WC_Ordoro_Resource {

	protected $endpoint = 'product';
	protected $threshold;
	protected $cost;
	protected $price;
	protected $weight;
	protected $category;
	protected $name;
	protected $sku;

	/**
	 * Set Product Threshold
	 *
	 * @since 1.0
	 * @param int $threshold sets specific product threshold
	 */
	public function set_threshold( $threshold ) {
		$this->threshold = $threshold;
	}

	/**
	 * Has Product Threshold
	 *
	 * @since 1.0
	 * @return true or false
	 */
	public function has_threshold() {
		return $this->threshold ? true : false;
	}

	/**
	 * Update Product Threshold
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $threshold sets specific product threshold
	 * @param int $warehouse_id define which warehouse to update
	 */
	public function update_threshold( $sku, $threshold, $warehouse_id = null ) {
		$this->set_threshold( $threshold );
		$args = array( 
			'low_stock_threshold' => $this->threshold
		);
		if( empty( $warehouse_id ) ) {
			$warehouse_id = $this->warehouse;
		}
		$this->set_path( array( $sku, 'warehouse/'. $warehouse_id . '/' ) );
		$this->set_body( $args );
		return $this->put();
	}

	/**
	 * Sets a the location for the warehouse
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $location updates the specific warehouse location of the product
	 * @param int $warehouse_id define which warehouse to update
	 * @return array
	 */
	public function set_location_in_warehouse( $sku, $location = '', $warehouse_id = null ) {
		$args = array(
			'location_in_warehouse' => $location,
		);
		if( empty( $warehouse_id ) ) {
			$warehouse_id = $this->warehouse;
		}
		$this->set_path( array( $sku, 'warehouse/' . $warehouse_id . '/' ) );
		$this->set_body( $args );
		return $this->put();
	}

	/**
	 * Adds supplier to product
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $supplier define which supplier to add
	 * @param array $args
	 * @return array
	 */
	public function add_supplier( $sku, $supplier, $args = array() ) {
		$defaults = array(
			'supplier_price' => 0,
			'is_default'     => false,
			'supplier_sku'   => null,
			'min_order_qty'  => 1
		);
		$args = wp_parse_args( $args, $defaults );
		$this->set_body( $args );
		$this->set_path( array( $sku, 'supplier/' . $supplier . '/' ) );
		return $this->put();
	}

	/**
	 * Set a Dropshiper for the specific product
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $supplier define which supplier to dropship
	 * @return array
	 */
	public function set_dropshiper( $sku, $supplier ) {
		$this->set_path( array( $sku, 'default_dropshipper/'. $supplier . '/' ) );
		return $this->post();
	}

	/**
	 * Set a Dropshiper for the specific product
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @return array
	 */
	public function delete_dropshiper( $sku ) {
		$this->set_path( array( $sku, 'default_dropshipper' ) );
		return $this->delete();
	}

	/**
	 * Removes supplier to product
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $supplier define which supplier to delete
	 * @return array
	 */
	public function delete_supplier( $sku, $supplier ) {
		$this->set_path( array( $sku, 'supplier/' . $supplier . '/' ) );
		return $this->delete();
	}

	/**
	 * Set default supplier
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $supplier define which supplier to add
	 * @return array
	 */
	public function set_default_supplier( $sku, $supplier ) {
		$this->set_path( array( $sku, 'default_supplier/' . $supplier . '/' ) );
		return $this->post();
	}
	
	/**
	 * Removes default supplier
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param int $supplier define which supplier to add
	 * @return array
	 */
	public function delete_default_supplier( $sku ) {
		$this->set_path( array( $sku, 'default_supplier' ) );
		return $this->delete();
	}

	/**
	 * Gets the total number of products in ordoro
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_count() {
		$this->set_path( array( 'counts' ) );
		return $this->get();
	}

	/**
	 * Gets the amount sold for a specific product in ordoro
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @return array
	 */
	public function monthly_sales( $sku ) {
		$this->set_path( array( $sku, 'n_day_sales' ) );
		return $this->get();
	}

	/**
	 * Archives a specific product in ordoro
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @return array
	 */
	public function archive( $sku ) {
		$this->set_path( array( $sku, 'archive' ) );
		return $this->post();
	}	

	/**
	 * unarchives a specific product in ordoro
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @return array
	 */
	public function unarchive( $sku ) {
		$this->set_path( array( $sku, 'unarchive' ) );
		return $this->post();
	}	

	/**
	 * Updates Product
	 *
	 * @since 1.0
	 * @param string $sku product SKU
	 * @param string $arg product args
	 * @return array
	 */
	public function update( $sku, $args = array() ) {
		$defaults = array( 
			'sku'      => $this->sku,
			'name'     => $this->name,
			'cost'     => $this->cost,
			'price'    => $this->price,
			'weight'   => $this->weight,
			'category' => $this->category,
		);
		$args = wp_parse_args( $args, $defaults );
		$this->set_id( $sku );
		$this->set_body( $args );
		return $this->put();
	}

}