import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ReturnToShop } from '../../icons/return-to-shop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ReturnToShop,
} );
