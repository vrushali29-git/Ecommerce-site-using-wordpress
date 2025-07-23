import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductTitle } from '../../icons/product-title.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductTitle,
} );
