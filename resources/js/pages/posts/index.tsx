import { Head } from '@inertiajs/react';
import { PlusCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import { useEffect } from 'react';
import PostCard from '@/components/post-card';
import PostsLayout from '@/layouts/posts-layout';
import { type BreadcrumbItem } from '@/types';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/site/posts'
    }
];

interface Post {
    id: number;
    user_id: number;
    title: any;
    content: any;
    privacy: string;
    is_promoted: boolean;
    is_archived: boolean;
    event_name?: string;
    event_date_time?: string;
    created_at: string;
    slug?: string;
}

interface IndexProps {
    auth: {
        user: {
            id: number;
        };
    };
    posts: Post[];
    success?: string;
}

export default function Index({ auth, posts = [], success }: IndexProps) {
    const handleDelete = (postId: number) => {
        toast('Are you sure you want to delete this post?', {
            action: {
                label: 'Delete',
                onClick: () => {
                    router.delete(route('site.posts.destroy', postId), {
                        onSuccess: () => toast.success('Post deleted successfully'),
                        onError: () => toast.error('Failed to delete post')
                    });
                }
            },
            cancel: {
                label: 'Cancel'
            }
        });
    };

    useEffect(() => {
        if (success) {
            toast.success(success);
        }
    }, [success]);

    return (
        <PostsLayout
            auth={auth}
            posts={posts}
            breadcrumbs={breadcrumbs}
            title="Your Posts"
            description="Manage and create new posts"
        >
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
                <div className="p-8 text-center border border-dashed rounded-md">
                    <p className="text-sm text-muted-foreground">You haven't created any posts yet.</p>
                    <Button asChild className="mt-4">
                        <Link href={route('site.posts.create')}>Create your first post</Link>
                    </Button>
                </div>
            ) : (
                <div className="space-y-4">
                    {posts.map((post) => (
                        <PostCard
                            key={post.id}
                            post={post}
                            onDelete={handleDelete}
                            canEdit={auth.user.id === post.user_id}
                            canDelete={auth.user.id === post.user_id}
                        />
                    ))}
                </div>
            )}
        </PostsLayout>
    );
}
