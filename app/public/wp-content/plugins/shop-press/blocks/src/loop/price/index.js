import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductPrice } from '../../icons/product-price.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductPrice,
} );
