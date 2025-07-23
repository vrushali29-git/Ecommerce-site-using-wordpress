import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CartEmptyMessage } from '../../icons/cart-empty-message.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CartEmptyMessage,
} );
