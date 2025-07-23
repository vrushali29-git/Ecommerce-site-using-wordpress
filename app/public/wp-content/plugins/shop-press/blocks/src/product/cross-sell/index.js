import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CrossSell } from '../../icons/cross-sell.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CrossSell,
} );
