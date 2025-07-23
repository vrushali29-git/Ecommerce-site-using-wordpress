import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CompareLoop } from '../../icons/compare-loop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CompareLoop,
} );
