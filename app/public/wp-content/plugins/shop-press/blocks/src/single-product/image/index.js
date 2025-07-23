import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductImages } from '../../icons/product-images.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductImages,
} );
