import TheHomePage from '../components/Pages/TheHomePage'
// import TheObjectsPage from '../components/Pages/TheObjectsPage'
import TheObjectsPage from '../components/TheObjectsPage'
// import TheNcaPage from '../components/Pages/TheNcaPage'
import TheNcaPage from '../components/TheNcaPage'
import TheSourcesPage from '../components/Pages/TheSourcesPage'
import TheSourcesCreatePage from '../components/Pages/TheSourcesCreatePage'
import TheSourcesEditPage from '../components/Pages/TheSourcesEditPage'
import TheParsersPage from '../components/Pages/TheParsersPage'
import TheParsersCreatePage from '../components/Pages/TheParsersCreatePage'
import TheParsersEditPage from '../components/Pages/TheParsersEditPage'
import TheParsersSettingsPage from '../components/Pages/TheParsersSettingsPage'
import TheParsersDebugPage from '../components/Pages/TheParsersDebugPage'
import TheProfilePage from '../components/Pages/TheProfilePage'
import TheLoginPage from '../components/Pages/TheLoginPage'
import TheRegisterPage from '../components/Pages/TheRegisterPage'

export default [
    {
        name: 'home',
        path: '/',
        component: TheHomePage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'objects',
        path: '/objects',
        component: TheObjectsPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'nca',
        path: '/nca',
        component: TheNcaPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'sources',
        path: '/sources',
        component: TheSourcesPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'sources.create',
        path: '/sources/create',
        component: TheSourcesCreatePage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'sources.edit',
        path: '/sources/:id/edit',
        component: TheSourcesEditPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'parsers',
        path: '/parsers',
        component: TheParsersPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'parsers.create',
        path: '/parsers/create',
        component: TheParsersCreatePage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'parsers.edit',
        path: '/parsers/:id/edit',
        component: TheParsersEditPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'parsers.settings',
        path: '/parsers/:id/settings',
        component: TheParsersSettingsPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'parsers.debug',
        path: '/parsers/:id/debug',
        component: TheParsersDebugPage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'profile',
        path: '/profile',
        component: TheProfilePage,

        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'login',
        path: '/login',
        component: TheLoginPage,

        meta: {
            requiresGuest: true
        }
    },
    {
        name: 'register',
        path: '/register',
        component: TheRegisterPage,

        meta: {
            requiresGuest: true
        }
    }
]
