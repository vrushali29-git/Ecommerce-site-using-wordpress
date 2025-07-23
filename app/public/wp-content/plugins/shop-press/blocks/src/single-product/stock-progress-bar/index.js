import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as StockProgressBar } from '../../icons/stock-progress-bar.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: StockProgressBar,
} );
