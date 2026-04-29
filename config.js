// ================================================
// config.js — Dynamic BASE_URL for all HTML pages
// ================================================
// This auto-detects localhost vs live server.
// Include this script BEFORE any fetch() calls.

(function() {
    const host = window.location.hostname;
    const isLocal = host === 'localhost' || host === '127.0.0.1';

    window.BASE_URL = isLocal
        ? window.location.origin + '/htdocs/backend/api/'
        : window.location.origin + '/backend/api/';

    window.UPLOADS_URL = isLocal
        ? window.location.origin + '/htdocs/backend/uploads/'
        : window.location.origin + '/backend/uploads/';
})();
