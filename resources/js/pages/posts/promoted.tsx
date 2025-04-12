import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Promoted', href: '/site/posts/promoted' },
];

export default function Promoted({ auth, posts = [] }: { auth: any; posts: any[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Promoted Posts" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall title="Promoted Posts" description="View your promoted posts." />

                    <div className="flex justify-end">
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.index')}>Back to Posts</Link>
                        </Button>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-md border border-dashed p-8 text-center">
                            <p className="text-sm text-muted-foreground">No promoted posts found.</p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {posts.map((post) => (
                                <div key={post.id} className="rounded-lg border p-4 bg-yellow-50">
                                    <h3 className="font-medium">{post.title}</h3>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
