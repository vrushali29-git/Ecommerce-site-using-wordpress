import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductWeight } from '../../icons/product-weight.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductWeight,
} );
