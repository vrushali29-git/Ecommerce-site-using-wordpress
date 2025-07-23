import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductDiscount } from '../../icons/product-discount.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductDiscount,
} );
