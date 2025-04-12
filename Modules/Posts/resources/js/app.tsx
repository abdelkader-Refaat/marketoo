import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';

// Main layouts
import AppLayout from '@/layouts/app-layout';

// Module layouts
import PostsLayout from '@posts/layout';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        // Split the component name to determine the module (if any)
        const [moduleName, ...componentPath] = name.split('/');

        // Handle components from the Posts module
        if (moduleName.toLowerCase() === 'posts') {
            const page = await resolvePageComponent(
                `./pages/${name}.tsx`,
                import.meta.glob('/Modules/Posts/Resources/js/pages/**/*.tsx')
            );
            // Set the default layout for Posts module pages
            page.default.layout = page.default.layout || ((page) => (
                <AppLayout>
                    <PostsLayout>{page}</PostsLayout>
                </AppLayout>
            ));
            return page;
        }

        // Handle components from the main app
        const page = await resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx')
        );
        // Set the default layout for main app pages
        page.default.layout = page.default.layout || ((page) => <AppLayout>{page}</AppLayout>);
        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563'
    }
});

// This will set light / dark mode on load...
initializeTheme();
