import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ShopProducts } from '../../icons/shop-products.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ShopProducts,
} );
