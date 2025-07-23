import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as AdditionalFields } from '../../icons/additional-fields.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: AdditionalFields,
} );
