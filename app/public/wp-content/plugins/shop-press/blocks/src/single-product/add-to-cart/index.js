import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as AddToCart } from '../../icons/add-to-cart.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: AddToCart,
} );
