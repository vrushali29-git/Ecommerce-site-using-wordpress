import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CartTotal } from '../../icons/cart-total.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CartTotal,
} );
