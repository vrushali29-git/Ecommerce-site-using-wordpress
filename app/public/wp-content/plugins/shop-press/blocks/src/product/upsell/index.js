import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as UpSell } from '../../icons/up-sell.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: UpSell,
} );
