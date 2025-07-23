import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductSKU } from '../../icons/product-sku.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductSKU,
} );
