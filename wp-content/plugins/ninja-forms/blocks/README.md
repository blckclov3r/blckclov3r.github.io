# Ninja Forms Views

Ninja Forms Views is a submissions table display application integrated with the WordPress Block Editor.

## Development

Compilation, testing and local development are documented in the main plugin readme file.

## Key Concepts

The application is deployed in two main parts, the JavaScript "block" and the PHP REST API.

### The "Block"

The JavaScript "block" consists of two entry points: the block editor (`entry point blocks.js`) and the "front end" (entry point `render.js`).

Both entry points share the `<FormsSubmissionsTable />` component, which is an implementation of the `react-table-component` - a headless table UI component for React.

### Things to Know

#### Block Attributes on the "Front End"

Block attributes are passed to the block's `render_callback` function, but are not otherwise directly available in a "front end" script. Additionally, attributes are per-block-instance.

Because of this, each of the block attributes are localized within the `render_callback` as a JSON encoded array, which is then parsed when rendered to the DOM.

#### Form Data Store with @wordpress/data

Form data is managed by a centralized data registry using `@wordpress/data`). The `Store` provides an API for `select`ing data from the centralized data registry, known as `selectors`. This includes support for resolving missing data from an external source, which is fulfilled by a REST API.

#### Block Alignment Implementation

The `<BlockAlignmentToolbar />` provides alignment and **width** settings which allow the width of a block to break-out of the page container - this includes **wide** and **full** widths, which are often use for "cover images".

Due to the nature of tables, the `<BlockAlignmentToolbar />` is implemented as a means by which to provide additional width to a table display.

Unfortunately, this is not a straight forward implementation, because of the different needs of the block editor vs the page display of a block.

- The `registerBlockType` definition uses the `getEditWrapperProps()` property to inject a `data-align` attribute to the Edit component wrapper.
- The `render_callback` function parses the block attributes to inject an additional `align{alignment}` class name to the `<div />` placeholder.

#### The REST API

The REST API provides asynchronous access to the Ninja Forms data: Forms, Fields, and Submissions.

Currently, all form data is served via a single Route (`ninja-forms-views/forms`), but will be refactored into seperate endpoints for forms, fields, and submissions - with submissions supporting pagination.


