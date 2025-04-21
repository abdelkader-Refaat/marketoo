// pages/posts/edit.tsx
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { router } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { ErrorMessage } from '@/components/error-message';

interface EditPostProps {
    post: {
        id: number;
        title: any;
        content: any;
        privacy: string;
        is_promoted: boolean;
        slug: string;
        event_name?: string;
        event_date_time?: string;
        event_description?: string;
    };
    errors?: Record<string, string>;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Edit', href: '' }
];

const getValue = (value: any) => {
    if (!value) return '';
    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value);
            return parsed.en || Object.values(parsed)[0] || '';
        } catch {
            return value;
        }
    }
    return value.en || Object.values(value)[0] || '';
};

export default function Edit({ post, errors }: EditPostProps) {
    const { data, setData, processing } = useForm({
        title: getValue(post.title),
        content: getValue(post.content),
        slug: post.slug,
        privacy: post.privacy,
        is_promoted: post.is_promoted,
        event_name: post.event_name || '',
        event_date_time: post.event_date_time || '',
        event_description: post.event_description || ''
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.put(route('site.posts.update', post.id), data, {
            onSuccess: () => toast.success('Post updated successfully'),
            onError: () => toast.error('Failed to update post')
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Post" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <h2 className="text-2xl font-semibold">Edit Post</h2>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <Label htmlFor="title">Title</Label>
                            <Input
                                id="title"
                                name="title"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                placeholder="Enter post title"
                                required
                            />
                            <ErrorMessage message={errors?.title} />
                        </div>
                        <div>
                            <Label htmlFor="content">Content</Label>
                            <Textarea
                                id="content"
                                name="content"
                                value={data.content}
                                onChange={(e) => setData('content', e.target.value)}
                                placeholder="Enter post content"
                                required
                                rows={6}
                            />
                            <ErrorMessage message={errors?.content} />
                        </div>
                        <div>
                            <Label htmlFor="slug">Slug</Label>
                            <Input
                                id="slug"
                                name="slug"
                                value={data.slug}
                                onChange={(e) => setData('slug', e.target.value)}
                                placeholder="post-slug"
                                required
                            />
                            <ErrorMessage message={errors?.slug} />
                        </div>
                        <div>
                            <Label htmlFor="privacy">Privacy</Label>
                            <Select
                                value={data.privacy}
                                onValueChange={(value) => setData('privacy', value)}
                                required
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select privacy" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="public">Public</SelectItem>
                                    <SelectItem value="private">Private</SelectItem>
                                    <SelectItem value="friends">Friends Only</SelectItem>
                                </SelectContent>
                            </Select>
                            <ErrorMessage message={errors?.privacy} />
                        </div>
                        <div className="flex items-center gap-2">
                            <input
                                type="checkbox"
                                id="is_promoted"
                                name="is_promoted"
                                checked={data.is_promoted}
                                onChange={(e) => setData('is_promoted', e.target.checked)}
                                className="w-4 h-4"
                            />
                            <Label htmlFor="is_promoted">Promoted</Label>
                        </div>
                        <div className="pt-4 mt-4 border-t">
                            <h3 className="mb-4 font-medium">Event Details (Optional)</h3>
                            <div className="space-y-4">
                                <div>
                                    <Label htmlFor="event_name">Event Name</Label>
                                    <Input
                                        id="event_name"
                                        name="event_name"
                                        value={data.event_name}
                                        onChange={(e) => setData('event_name', e.target.value)}
                                        placeholder="Event name"
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="event_date_time">Event Date & Time</Label>
                                    <Input
                                        id="event_date_time"
                                        name="event_date_time"
                                        type="datetime-local"
                                        value={data.event_date_time}
                                        onChange={(e) => setData('event_date_time', e.target.value)}
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="event_description">Event Description</Label>
                                    <Textarea
                                        id="event_description"
                                        name="event_description"
                                        value={data.event_description}
                                        onChange={(e) => setData('event_description', e.target.value)}
                                        placeholder="Event description"
                                        rows={4}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="flex justify-end gap-2 pt-4">
                            <Button type="button" variant="outline" asChild>
                                <Link href={route('site.posts.index')}>Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}