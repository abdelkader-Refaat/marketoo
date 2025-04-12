import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Settings', href: '/site/posts/settings' }
];

export default function Settings({ auth }: { auth: any }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Post Settings" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall title="Post Settings" description="Manage your post settings." />

                    <div className="rounded-lg border p-4">
                        <p className="text-muted-foreground">Settings functionality coming soon.</p>
                    </div>

                    <div className="flex justify-end">
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.index')}>Back to Posts</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
