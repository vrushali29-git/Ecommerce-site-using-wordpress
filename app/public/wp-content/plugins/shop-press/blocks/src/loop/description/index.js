import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductDescription } from '../../icons/product-description.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductDescription,
} );
