import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CompareButton } from '../../icons/compare-button.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CompareButton,
} );
