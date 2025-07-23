import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductCategoriesLoop } from '../../icons/categories-loop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductCategoriesLoop,
} );
