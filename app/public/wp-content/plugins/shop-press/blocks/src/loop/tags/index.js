import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as TagsLoop } from '../../icons/tags-loop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: TagsLoop,
} );
