import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Globe, Lock, Users, Calendar } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'View', href: '' }
];

const parseJson = (value: any) => {
    if (!value) return null;
    if (typeof value === 'string') {
        try {
            return JSON.parse(value);
        } catch {
            return { en: value };
        }
    }
    return value;
};

const getTitle = (title: any) => {
    const parsed = parseJson(title);
    return parsed?.en || Object.values(parsed || {})[0] || 'Untitled';
};

const getContent = (content: any) => {
    const parsed = parseJson(content);
    return parsed?.en || Object.values(parsed || {})[0] || 'No content available.';
};

const getPrivacyIcon = (privacy: string) => {
    const iconMap: Record<string, JSX.Element> = {
        public: <Globe size={16} className="text-green-500" />,
        private: <Lock size={16} className="text-red-500" />,
        friends: <Users size={16} className="text-blue-500" />
    };
    return iconMap[privacy] || <Globe size={16} className="text-gray-500" />;
};

export default function Show({ post }: { post: any }) {
    if (!post) return <div className="p-6 text-center text-red-500">Post not found.</div>;

    const {
        privacy = 'public',
        event_name,
        event_date_time,
        created_at,
        slug
    } = post;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View Post" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall
                        title={getTitle(post.title)}
                        description="View the details of your post."
                    />

                    <div className="rounded-lg border p-4">
                        <div className="mb-4">
                            <h3 className="text-lg font-medium">Content</h3>
                            <p className="text-muted-foreground whitespace-pre-wrap">
                                {getContent(post.content)}
                            </p>
                        </div>

                        <div className="text-sm text-muted-foreground space-y-2">
                            <div className="flex items-center gap-1">
                                {getPrivacyIcon(privacy)}
                                <span className="capitalize">{privacy}</span>
                            </div>

                            {event_name && (
                                <div className="flex items-center gap-1">
                                    <Calendar size={16} />
                                    {event_name}
                                </div>
                            )}

                            {created_at && (
                                <div>Created: {new Date(created_at).toLocaleDateString()}</div>
                            )}

                            {event_date_time && (
                                <div>
                                    Event: {new Date(event_date_time).toLocaleString()}
                                </div>
                            )}

                            {slug && <div>Slug: {slug}</div>}
                        </div>
                    </div>

                    <div className="flex justify-end space-x-2">
                        <Button asChild variant="outline">
                            <Link href={route('site.posts.index')}>Back to Posts</Link>
                        </Button>
                        <Button asChild>
                            <Link href={route('site.posts.edit', post.id)}>Edit Post</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
