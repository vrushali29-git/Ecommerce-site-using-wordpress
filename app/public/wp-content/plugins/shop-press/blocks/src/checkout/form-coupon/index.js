import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CouponForm } from '../../icons/coupon-form.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CouponForm,
} );
