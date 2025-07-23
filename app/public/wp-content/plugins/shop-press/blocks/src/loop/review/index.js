import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductReview } from '../../icons/product-review.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductReview,
} );
