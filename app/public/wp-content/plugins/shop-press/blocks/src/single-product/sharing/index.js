import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductSharing } from '../../icons/product-sharing.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductSharing,
} );
