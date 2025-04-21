import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';

export default function Forbidden() {
    return (
        <AppLayout>
            <Head title="Access Denied" />
            <div className="flex flex-col items-center justify-center min-h-screen p-4 text-center">
                <div className="max-w-md space-y-4">
                    <h1 className="text-4xl font-bold text-red-500">403</h1>
                    <h2 className="text-2xl font-semibold">Access Denied</h2>
                    <p className="text-muted-foreground">
                        You don't have permission to access this resource.
                    </p>
                    <Button asChild>
                        <Link href={route('site.posts.index')}>Back to Posts</Link>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}