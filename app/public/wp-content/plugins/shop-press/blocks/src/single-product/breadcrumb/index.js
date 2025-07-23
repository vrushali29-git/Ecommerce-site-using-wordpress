import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductBreadcrumb } from '../../icons/product-breadcrumb.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductBreadcrumb,
} );
