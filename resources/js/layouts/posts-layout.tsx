import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/react';
import React from 'react';

interface PostsLayoutProps {
    auth: any;
    posts: any[];
    breadcrumbs: BreadcrumbItem[];
    title: string;
    description: string;
    showBackButton?: boolean;
    children?: React.ReactNode;
}

export default function PostsLayout({
                                        auth,
                                        posts = [],
                                        breadcrumbs,
                                        title,
                                        description,
                                        showBackButton = false,
                                        children
                                    }: PostsLayoutProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs} auth={auth}>
            <Head title={title} />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <div className="flex items-center justify-between">
                        <HeadingSmall title={title} description={description} />
                        {showBackButton && (
                            <Button asChild variant="outline">
                                <Link href={route('site.posts.index')}>Back to Posts</Link>
                            </Button>
                        )}
                    </div>

                    {children}
                </div>
            </div>
        </AppLayout>
    );
}
