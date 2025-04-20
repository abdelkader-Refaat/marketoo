import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Slider from '@/components/dashboard/slider';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    }
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6">
                {/* Slider section */}
                <div className="w-full">
                    <Slider />
                </div>

                {/* Rest of your content */}
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    {/* Your cards will go here */}
                </div>

                <div
                    className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[60vh] flex-1 overflow-hidden rounded-xl border">
                    <h1>welocme</h1>
                    {/* Main content */}
                </div>
            </div>
        </AppLayout>
    );
}
