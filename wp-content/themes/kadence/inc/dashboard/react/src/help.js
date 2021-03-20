/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { withFilters } = wp.components;

export const HelpTab = () => {
	return (
		<div className="kadence-desk-help-inner">
			<h2>{ __( 'Welcome to Kadence!', 'kadence' ) }</h2>
			<p>{ __( 'You are going to love working with this theme! View the video below to get started with our video tutorials or click the view knowledge base button below to see all the documentation.', 'kadence' ) }</p>
			<div className="video-container">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/GqEecMF7WtE" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<a href="https://kadence-theme.com/learn-kadence" className="kadence-desk-button" target="_blank">{ __( 'Video Tutorials', 'kadence' ) }</a><a href="https://kadence-theme.com/knowledge-base/" className="kadence-desk-button kadence-desk-button-second" target="_blank">{ __( 'View Knowledge Base', 'kadence' ) }</a>
		</div>
	);
};

export default withFilters( 'kadence_theme_help' )( HelpTab );