import { Globe, Lock, Users, Repeat, Calendar, Eye, Edit, Trash2 } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { toast } from 'sonner';
import { JSX } from 'react';

interface PostCardProps {
    post: {
        id: number;
        title: any;
        content?: any;
        privacy: string;
        is_promoted?: boolean;
        repost_id?: number;
        event_name?: string;
        event_date_time?: string;
        created_at: string;
        slug?: string;
        user_id: number;
    };
    onDelete: (id: number) => void;
    canEdit: boolean;
    canDelete: boolean;
}

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

export default function PostCard({ post, onDelete, canEdit, canDelete }: PostCardProps) {
    return (
        <div className={`rounded-lg border p-4 ${post.is_promoted ? 'bg-yellow-50' : ''}`}>
            <div className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                    {post.repost_id && <Repeat size={16} className="text-blue-500" />}
                    <h3 className="font-medium">{getTitle(post.title)}</h3>
                    {post.is_promoted && (
                        <span className="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-800">
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

                    {canEdit && (
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={route('site.posts.edit', post.id)}>
                                <Edit size={16} />
                            </Link>
                        </Button>
                    )}

                    {canDelete && (
                        <Button
                            variant="ghost"
                            size="icon"
                            onClick={() => onDelete(post.id)}
                            className="text-destructive hover:text-destructive/90"
                        >
                            <Trash2 size={16} />
                        </Button>
                    )}
                </div>
            </div>

            {/* ... rest of your PostCard component ... */}
        </div>
    );
}