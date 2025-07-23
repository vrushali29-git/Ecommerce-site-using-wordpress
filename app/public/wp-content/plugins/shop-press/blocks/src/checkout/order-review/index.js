import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as OrderReview } from '../../icons/order-review.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: OrderReview,
} );
