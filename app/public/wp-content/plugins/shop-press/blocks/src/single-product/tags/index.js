import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductTags } from '../../icons/product-tags.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductTags,
} );
