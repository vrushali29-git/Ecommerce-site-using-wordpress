import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductAttributes } from '../../icons/product-attributes.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductAttributes,
} );
