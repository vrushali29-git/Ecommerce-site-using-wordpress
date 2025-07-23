import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductCategories } from '../../icons/product-categories.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductCategories,
} );
