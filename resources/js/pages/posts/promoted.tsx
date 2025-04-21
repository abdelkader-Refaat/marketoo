import PostsLayout from '@/layouts/posts-layout';
import { type BreadcrumbItem } from '@/types';
import PostCard from '@/components/post-card';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Promoted', href: '/site/posts/promoted' },
];

export default function Promoted({ auth, posts = [] }: { auth: any; posts: any[] }) {
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
            title="Promoted Posts"
            description="View your promoted posts"
            showBackButton
        >
            {posts.length === 0 ? (
                <div className="p-8 text-center border border-dashed rounded-md">
                    <p className="text-sm text-muted-foreground">No promoted posts found.</p>
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