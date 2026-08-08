import React from 'react';
import ReactDOM from 'react-dom';
import App from '@/components/App';
import { setConfig } from 'react-hot-loader';
import { startDemoMode } from '@/demo/bootstrap';

// Enable language support.
import './i18n';

// Prevents page reloads while making component changes which
// also avoids triggering constant loading indicators all over
// the place in development.
//
// @see https://github.com/gaearon/react-hot-loader#hook-support
setConfig({ reloadHooks: false });

const render = () => {
    ReactDOM.render(<App />, document.getElementById('app'));
};

startDemoMode()
    .then((status) => {
        // On the first opt-in load the worker installs and this page reloads. Rendering
        // before that reload would briefly show the login page and could issue real API calls.
        if (status !== 'reloading') render();
    })
    .catch((error) => {
        console.error('Demo bootstrap failed.', error);
        render();
    });
