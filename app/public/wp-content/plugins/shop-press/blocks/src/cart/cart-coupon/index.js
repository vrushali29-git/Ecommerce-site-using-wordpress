import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CartCoupon } from '../../icons/cart-coupon.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CartCoupon,
} );
