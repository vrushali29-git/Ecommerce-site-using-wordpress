import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as QuickViewButton } from '../../icons/quick-view-button.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: QuickViewButton,
} );
