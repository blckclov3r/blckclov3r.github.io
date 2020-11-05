/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { withFilters } = wp.components;

export const HelpTab = () => {
	return (
		<Fragment>
			<p>{ __( 'Coming soon!', 'kadence' ) }</p>
		</Fragment>
	);
};

export default withFilters( 'kadence_theme_help' )( HelpTab );