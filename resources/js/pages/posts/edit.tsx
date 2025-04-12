import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import PostsLayout from '@/posts/layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { router } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Edit', href: '' }
];

const getTitle = (title: any) => {
    if (!title) return '';
    if (typeof title === 'string') {
        try {
            const parsed = JSON.parse(title);
            return parsed.en || Object.values(parsed)[0] || '';
        } catch {
            return title;
        }
    }
    return title.en || Object.values(title)[0] || '';
};

const getContent = (content: any) => {
    if (!content) return '';
    if (typeof content === 'string') {
        try {
            const parsed = JSON.parse(content);
            return parsed.en || Object.values(parsed)[0] || '';
        } catch {
            return content;
        }
    }
    return content.en || Object.values(content)[0] || '';
};

export default function Edit({ post }: { post: any }) {
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);
        router.put(route('site.posts.update', post.id), formData, {
            forceFormData: true
        });

    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Post" />
            <PostsLayout>
                <div className="space-y-6">
                    <h2 className="text-2xl font-semibold">Edit Post</h2>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <Label htmlFor="title">Title (English)</Label>
                            <Input
                                id="title"
                                name="title"
                                defaultValue={getTitle(post.title)}
                                placeholder="Enter post title"
                                required
                            />
                        </div>
                        <div>
                            <Label htmlFor="content">Content (English)</Label>
                            <Textarea
                                id="content"
                                name="content"
                                defaultValue={getContent(post.content)}
                                placeholder="Enter post content"
                                required
                            />
                        </div>
                        <div>
                            <Label htmlFor="slug">Slug</Label>
                            <Input
                                id="slug"
                                name="slug"
                                defaultValue={post.slug}
                                placeholder="post-slug"
                                required
                            />
                        </div>
                        <div>
                            <Label htmlFor="privacy">Privacy</Label>
                            <Select name="privacy" defaultValue={post.privacy} required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select privacy" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="1">Public</SelectItem>
                                    <SelectItem value="2">Private</SelectItem>
                                    <SelectItem value="3">Unlisted</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label htmlFor="is_promoted">Promoted</Label>
                            <input
                                type="checkbox"
                                id="is_promoted"
                                name="is_promoted"
                                value="1"
                                defaultChecked={post.is_promoted}
                            />
                        </div>
                        <div>
                            <Label htmlFor="event_name">Event Name (Optional)</Label>
                            <Input
                                id="event_name"
                                name="event_name"
                                defaultValue={post.event_name}
                                placeholder="Event name"
                            />
                        </div>
                        <div>
                            <Label htmlFor="event_date_time">Event Date & Time (Optional)</Label>
                            <Input
                                id="event_date_time"
                                name="event_date_time"
                                type="datetime-local"
                                defaultValue={
                                    post.event_date_time
                                        ? new Date(post.event_date_time).toISOString().slice(0, 16)
                                        : ''
                                }
                            />
                        </div>
                        <div>
                            <Label htmlFor="event_description">Event Description (Optional)</Label>
                            <Textarea
                                id="event_description"
                                name="event_description"
                                defaultValue={post.event_description}
                                placeholder="Event description"
                            />
                        </div>
                        <Button type="submit">Update Post</Button>
                    </form>
                </div>
            </PostsLayout>
        </AppLayout>
    );
}
