import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as RelatedProducts } from '../../icons/related-products.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: RelatedProducts,
} );
