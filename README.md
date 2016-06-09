# WC-Ordoro-API
Integrates Ordoro API with Wocoomerce

## how to use
You can use the following functions

`ordoro()->product->get();`

Returns an array 10 products from Ordoro.

`ordoro()->product->get( 'sku number' );`

Returns an array of information for that specific sku number from Ordoro.

`ordoro()->product->update_threshold( sku, threshold );`

Updates the specified sku number's stock threshold in Ordoro.

`ordoro()->product->set_location_in_warehouse( sku, location );`

Updates the specific warehouse location of the given sku in Ordoro.

`ordoro()->product->archive( sku );`

Archives the given sku in Ordoro.

`ordoro()->product->unarchive( sku );`

Unarchives the given sku in Ordoro.
