// resources/js/posts/layout.tsx
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

const sidebarNavItems: NavItem[] = [
    {
        title: 'All Posts',
        url: '/posts',
        icon: null
    },
    {
        title: 'Create Post',
        url: '/posts/create',
        icon: null
    },
    {
        title: 'Drafts',
        url: '/posts/drafts',
        icon: null
    }
];

export default function PostsLayout({ children }: { children: React.ReactNode }) {
    const currentPath = window.location.pathname;

    return (
        <div className="px-4 py-6">
            <Heading
                title="Posts"
                description="Manage your blog posts and drafts"
            />

            <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
                <aside className="w-full max-w-xl lg:w-48">
                    <nav className="flex flex-col space-y-1">
                        {sidebarNavItems.map((item) => (
                            <Button
                                key={item.url}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn('w-full justify-start', {
                                    'bg-muted': currentPath === item.url
                                })}
                            >
                                <Link href={item.url} prefetch>
                                    {item.title}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 md:hidden" />

                <div className="flex-1 md:max-w-3xl">
                    <section className="space-y-8">{children}</section>
                </div>
            </div>
        </div>
    );
}
