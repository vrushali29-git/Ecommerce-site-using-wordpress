import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductSizeChart } from '../../icons/product-size-chart.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductSizeChart,
} );
