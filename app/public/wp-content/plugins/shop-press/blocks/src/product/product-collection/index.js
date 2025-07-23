import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductCollection } from '../../icons/product-collection.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductCollection,
} );
