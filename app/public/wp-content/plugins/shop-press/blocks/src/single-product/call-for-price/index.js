import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as CallForPrice } from '../../icons/call-for-price.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: CallForPrice,
} );
