/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { withFilters } = wp.components;

export const ProModules = () => {
	return (
		<Fragment>
		</Fragment>
	);
};

export default withFilters( 'kadence_theme_pro_modules' )( ProModules );