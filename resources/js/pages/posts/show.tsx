// pages/posts/show.tsx
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Globe, Lock, Users, Calendar } from 'lucide-react';
import { toast } from 'sonner';

interface ShowPostProps {
    post: {
        id: number;
        title: any;
        content: any;
        privacy: string;
        event_name?: string;
        event_date_time?: string;
        created_at: string;
        slug?: string;
        user: {
            name: string;
        };
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'View', href: '' }
];

const getValue = (value: any) => {
    if (!value) return null;
    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value);
            return parsed.en || Object.values(parsed)[0] || null;
        } catch {
            return value;
        }
    }
    return value.en || Object.values(value)[0] || null;
};

const getPrivacyIcon = (privacy: string) => {
    const iconMap: Record<string, JSX.Element> = {
        public: <Globe size={16} className="text-green-500" />,
        private: <Lock size={16} className="text-red-500" />,
        friends: <Users size={16} className="text-blue-500" />
    };
    return iconMap[privacy] || <Globe size={16} className="text-gray-500" />;
};

export default function Show({ post }: ShowPostProps) {
    if (!post) {
        toast.error('Post not found');
        return (
            <div className="p-6 text-center text-red-500">
                Post not found
            </div>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View Post" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold">{getValue(post.title) || 'Untitled'}</h2>
                            <p className="text-muted-foreground">By {post.user.name}</p>
                        </div>
                        <div className="flex gap-2">
                            <Button asChild variant="outline">
                                <Link href={route('site.posts.index')}>Back to Posts</Link>
                            </Button>
                            <Button asChild>
                                <Link href={route('site.posts.edit', post.id)}>Edit Post</Link>
                            </Button>
                        </div>
                    </div>

                    <div className="p-6 border rounded-lg">
                        <div className="prose max-w-none">
                            <p className="whitespace-pre-wrap">
                                {getValue(post.content) || 'No content available.'}
                            </p>
                        </div>

                        <div className="pt-6 mt-6 space-y-3 text-sm border-t text-muted-foreground">
                            <div className="flex items-center gap-2">
                                {getPrivacyIcon(post.privacy)}
                                <span className="capitalize">{post.privacy}</span>
                            </div>

                            {post.event_name && (
                                <div className="flex items-center gap-2">
                                    <Calendar size={16} />
                                    <span>{post.event_name}</span>
                                </div>
                            )}

                            <div>
                                Created: {new Date(post.created_at).toLocaleDateString()}
                            </div>

                            {post.event_date_time && (
                                <div>
                                    Event: {new Date(post.event_date_time).toLocaleString()}
                                </div>
                            )}

                            {post.slug && (
                                <div>
                                    Slug: {post.slug}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}