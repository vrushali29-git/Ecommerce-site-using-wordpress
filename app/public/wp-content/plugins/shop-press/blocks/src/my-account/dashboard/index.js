import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as Dashboard } from '../../icons/dashboard.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: Dashboard,
} );
