import Page from '../vendor/dscheinah/sx-js/src/Page.js';
import State from '../vendor/dscheinah/sx-js/src/State.js';
import * as files from './repository/files.js';
// By separating the helpers to its own namespace they do not need to packed to an object here.
import * as helper from './helper.js';

// The repository that will handle the requests to the backend.
// Create the global state manager.
const state = new State();
// Create the page manager responsible for lazy loading pages and handling the history and page stack.
// The state manager is used to trigger sx-show and sx-hide when the state of pages changes.
// The state event gets the ID of the page as payload.
const page = new Page(state, helper.element('#main'));

// A global state handler to show the loading animation.
// Use state.dispatch('loading', true) to trigger the animation and state.dispatch('loading', false) to stop it.
state.handle('loading', (payload, next) => {
    // The element is hidden by using visibility to not need extra CSS for positioning of the menu entries.
    helper.style('#loading', 'visibility', payload ? null : 'hidden');
    return next(payload);
});
// Always disable the loading animation when any loaded page is ready.
state.listen('sx-show', () => state.dispatch('loading', false));

// This is a simple example for async global state management.
state.handle('backend-data', (payload) => data.load(payload));
state.handle('files-selected', () => files.loadSelected());
state.handle('files-available', (term) => files.loadAvailable(term));
state.handle('files-save', (payload) => files.save(payload));

// Define all pages and load the main page. The ID defined here is globally used for:
//  - handling navigation by href or value (see above)
//  - registering scopes in pages
//  - payload of sx-show and sx-hide state events
// For real routing you can replace window.location.href with custom paths for each page.
page.add('files', 'pages/files.html', window.location.href);
page.add('files-select', 'pages/files/select.html', window.location.href);
// If used with routing this must be replaced with a check on the called route.
page.show('files');

// The app.js file is used as a kind of service manager for dependency injection.
// Import the file in pages to get access to the exported modules.
export {helper, page, state};
