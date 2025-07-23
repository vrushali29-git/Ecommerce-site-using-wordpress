import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductRating } from '../../icons/product-rating.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductRating,
} );
