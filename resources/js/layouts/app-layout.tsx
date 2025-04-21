import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { ToastProvider } from '@/components/toast-provider';

import { type BreadcrumbItem } from '@/types';
import React from 'react';

interface AppLayoutProps {
    children: React.ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children, breadcrumbs, ...props }: AppLayoutProps) {
    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
              <ToastProvider />
            {children}
        </AppLayoutTemplate>
    );
}
