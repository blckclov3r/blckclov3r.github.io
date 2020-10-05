/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { withFilters, TabPanel, Panel, PanelBody, PanelRow, Button } = wp.components;
export const StarterTab = () => {
	return (
		<Fragment>
			<p>{ __( 'This area is for Starter Sites.', 'kadence' ) }</p>
		</Fragment>
	);
};

export default withFilters( 'kadence_theme_starters' )( StarterTab );