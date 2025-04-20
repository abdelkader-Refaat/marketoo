import { Head } from '@inertiajs/react';
import { PlusCircle, Repeat, Eye, Edit, Trash2, Globe, Lock, Users, Calendar } from 'lucide-react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import { JSX, useEffect } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/site/posts'
    }
];

const getTitle = (title: any) => {
    if (!title) return 'Untitled';
    if (typeof title === 'string') {
        try {
            const parsed = JSON.parse(title);
            return parsed.en || Object.values(parsed)[0] || 'Untitled';
        } catch {
            return title;
        }
    }
    return title.en || Object.values(title)[0] || 'Untitled';
};

const getPrivacyIcon = (privacy: string) => {
    const iconMap: Record<string, JSX.Element> = {
        public: <Globe size={16} className="text-green-500" />,
        private: <Lock size={16} className="text-red-500" />,
        friends: <Users size={16} className="text-blue-500" />
    };
    return iconMap[privacy] || <Globe size={16} className="text-gray-500" />;
};

export default function Index({ auth, posts = [], success }: { auth: any; posts: any[]; success?: string }) {
    const handleDelete = (postId: number) => {
        if (confirm('Are you sure you want to delete this post?')) {
            router.delete(route('site.posts.destroy', postId));
        }
    };

    useEffect(() => {
        if (success) {
            alert(success);
        }
    }, [success]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posts" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall title="Your Posts" description="Manage and create new posts" />

                    <div className="flex justify-end space-x-2">
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.events')}>Events</Link>
                        </Button>
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.promoted')}>Promoted</Link>
                        </Button>
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.archived')}>Archived</Link>
                        </Button>
                        <Button asChild>
                            <Link href={route('site.posts.create')} className="flex items-center gap-2">
                                <PlusCircle size={16} />
                                New Post
                            </Link>
                        </Button>
                    </div>

                    {posts.length === 0 ? (
                        <div className="rounded-md border border-dashed p-8 text-center">
                            <p className="text-sm text-muted-foreground">You haven't created any posts yet.</p>
                            <Button asChild className="mt-4">
                                <Link href={route('site.posts.create')}>Create your first post</Link>
                            </Button>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {posts.map((post) => (
                                <div
                                    key={post.id}
                                    className={`rounded-lg border p-4 ${post.is_promoted ? 'bg-yellow-50' : ''}`}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            {post.repost_id && <Repeat size={16} className="text-blue-500" />}
                                            <h3 className="font-medium">{getTitle(post.title)}</h3>
                                            {post.is_promoted && (
                                                <span
                                                    className="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">
                                                    Promoted
                                                </span>
                                            )}
                                        </div>

                                        <div className="flex items-center gap-2">
                                            <Button variant="ghost" size="icon" asChild>
                                                <Link href={route('site.posts.show', post.id)}>
                                                    <Eye size={16} />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon" asChild>
                                                <Link href={route('site.posts.edit', post.id)}>
                                                    <Edit size={16} />
                                                </Link>
                                            </Button>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => handleDelete(post.id)}
                                                className="text-destructive hover:text-destructive/90"
                                            >
                                                <Trash2 size={16} />
                                            </Button>
                                        </div>
                                    </div>

                                    <div className="mt-2 text-sm text-muted-foreground">
                                        <div className="flex flex-wrap items-center gap-x-4 gap-y-1">
                                            <div className="flex items-center gap-1">
                                                {getPrivacyIcon(post.privacy)}
                                                <span className="capitalize">{post.privacy}</span>
                                            </div>

                                            {post.event_name && (
                                                <div className="flex items-center gap-1">
                                                    <Calendar size={16} />
                                                    {post.event_name}
                                                </div>
                                            )}

                                            <div>Created {new Date(post.created_at).toLocaleDateString()}</div>

                                            {post.event_date_time && (
                                                <div>Event: {new Date(post.event_date_time).toLocaleString()}</div>
                                            )}
                                        </div>
                                    </div>

                                    {post.slug && (
                                        <div className="mt-1 text-xs text-muted-foreground">Slug: {post.slug}</div>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
