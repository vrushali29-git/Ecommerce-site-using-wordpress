import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductStock } from '../../icons/product-stock.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductStock,
} );
