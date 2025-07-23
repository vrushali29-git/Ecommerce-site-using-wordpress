import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as Compare } from '../icons/compare.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: Compare,
} );
