import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductContent } from '../../icons/product-content.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductContent,
} );
