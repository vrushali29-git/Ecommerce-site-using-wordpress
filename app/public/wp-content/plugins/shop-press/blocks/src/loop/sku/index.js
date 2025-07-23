import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as SKULoop } from '../../icons/sku-loop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: SKULoop,
} );
