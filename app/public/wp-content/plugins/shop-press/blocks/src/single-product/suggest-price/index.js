import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as SuggestPrice } from '../../icons/suggest-price.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: SuggestPrice,
} );
