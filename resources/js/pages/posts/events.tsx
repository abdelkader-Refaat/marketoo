// pages/posts/events.tsx
import PostsLayout from '@/layouts/posts-layout';
import { type BreadcrumbItem } from '@/types';
import PostCard from '@/components/post-card';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: route('site.posts.index') },
    { title: 'Events', href: route('site.posts.events') }
];

export default function Events({ auth, posts = [] }: { auth: any; posts: any[] }) {
    const handleDelete = (postId: number) => {
        if (confirm('Are you sure you want to delete this post?')) {
            router.delete(route('site.posts.destroy', postId));
        }
    };

    return (
        <PostsLayout
            auth={auth}
            posts={posts}
            breadcrumbs={breadcrumbs}
            title="Event Posts"
            description="View posts associated with events"
            showBackButton
        >
            {posts.length === 0 ? (
                <div className="p-8 text-center border border-dashed rounded-md">
                    <p className="text-sm text-muted-foreground">No event posts found.</p>
                </div>
            ) : (
                <div className="space-y-4">
                    {posts.map((post) => (
                        <PostCard key={post.id} post={post} onDelete={handleDelete} />
                    ))}
                </div>
            )}
        </PostsLayout>
    );
}