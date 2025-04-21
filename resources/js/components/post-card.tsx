import { Globe, Lock, Users, Repeat, Calendar, Eye, Edit, Trash2 } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

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

export default function PostCard({
    post,
    onDelete
}: {
    post: any;
    onDelete: (id: number) => void
}) {
    return (
        <div className={`rounded-lg border p-4 ${post.is_promoted ? 'bg-yellow-50' : ''}`}>
            <div className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                    {post.repost_id && <Repeat size={16} className="text-blue-500" />}
                    <h3 className="font-medium">{getTitle(post.title)}</h3>
                    {post.is_promoted && (
                        <span className="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">
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
                        onClick={() => onDelete(post.id)}
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
    );
}