import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CartTable } from '../../icons/cart-table.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CartTable,
} );
