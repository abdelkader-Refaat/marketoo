import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/react';

interface EventsProps {
    auth: any;
    posts: any[];
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: route('site.posts.index') },
    { title: 'Events', href: route('site.posts.events') }
];

export default function Events({ auth, posts = [] }: EventsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Event Posts" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold">Event Posts</h2>
                            <p className="text-muted-foreground">View posts associated with events</p>
                        </div>
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.index')}>Back to Posts</Link>
                        </Button>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-md border border-dashed p-8 text-center">
                            <p className="text-sm text-muted-foreground">No event posts found.</p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {posts.map((post) => (
                                <div key={post.id} className="rounded-lg border p-4">
                                    <h3 className="font-medium">{post.title}</h3>
                                    <p className="text-sm text-muted-foreground">{post.event_name}</p>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
