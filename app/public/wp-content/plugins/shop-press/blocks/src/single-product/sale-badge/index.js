import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductSaleBadge } from '../../icons/product-sale-badge.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductSaleBadge,
} );
