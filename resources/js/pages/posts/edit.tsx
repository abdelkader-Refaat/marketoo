import { Head, useForm, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'sonner';

interface EditPostProps {
    post: {
        id: number;
        title: string | Record<string, string>;
        content: string | Record<string, string>;
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

const parseValue = (value: string | Record<string, string>): string => {
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
    const { data, setData, processing, put } = useForm({
        title: parseValue(post.title),
        content: parseValue(post.content),
        slug: post.slug,
        privacy: post.privacy,
        is_promoted: post.is_promoted,
        event_name: post.event_name || '',
        event_date_time: post.event_date_time ?
            new Date(post.event_date_time).toISOString().slice(0, 16) : '',
        event_description: post.event_description || ''
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('site.posts.update', post.id), {
            onSuccess: () => toast.success('Post updated successfully'),
            onError: () => toast.error('Failed to update post')
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Post" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall title="Edit Post" description="Update the details of your post." />

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <Label htmlFor="title">Title</Label>
                            <Input
                                id="title"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                placeholder="Enter post title"
                            />
                            {errors?.title && <p className="text-sm text-destructive">{errors.title}</p>}
                        </div>

                        <div>
                            <Label htmlFor="content">Content</Label>
                            <Textarea
                                id="content"
                                value={data.content}
                                onChange={(e) => setData('content', e.target.value)}
                                placeholder="Enter post content"
                                rows={6}
                            />
                            {errors?.content && <p className="text-sm text-destructive">{errors.content}</p>}
                        </div>

                        <div>
                            <Label htmlFor="privacy">Privacy</Label>
                            <Select
                                value={data.privacy}
                                onValueChange={(value) => setData('privacy', value)}
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
                            {errors?.privacy && <p className="text-sm text-destructive">{errors.privacy}</p>}
                        </div>

                        <div>
                            <Label htmlFor="slug">Slug</Label>
                            <Input
                                id="slug"
                                value={data.slug}
                                onChange={(e) => setData('slug', e.target.value)}
                                placeholder="Enter post slug"
                            />
                            {errors?.slug && <p className="text-sm text-destructive">{errors.slug}</p>}
                        </div>

                        <div className="flex items-center gap-2">
                            <input
                                type="checkbox"
                                id="is_promoted"
                                checked={data.is_promoted}
                                onChange={(e) => setData('is_promoted', e.target.checked)}
                                className="w-4 h-4"
                            />
                            <Label htmlFor="is_promoted">Promoted</Label>
                        </div>

                        {/* Event Details Section */}
                        <div className="pt-4 mt-4 border-t">
                            <h3 className="mb-4 font-medium">Event Details (Optional)</h3>
                            <div className="space-y-4">
                                <div>
                                    <Label htmlFor="event_name">Event Name</Label>
                                    <Input
                                        id="event_name"
                                        value={data.event_name}
                                        onChange={(e) => setData('event_name', e.target.value)}
                                        placeholder="Enter event name"
                                    />
                                    {errors?.event_name && <p className="text-sm text-destructive">{errors.event_name}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="event_date_time">Event Date & Time</Label>
                                    <Input
                                        id="event_date_time"
                                        type="datetime-local"
                                        value={data.event_date_time}
                                        onChange={(e) => setData('event_date_time', e.target.value)}
                                    />
                                    {errors?.event_date_time && <p className="text-sm text-destructive">{errors.event_date_time}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="event_description">Event Description</Label>
                                    <Textarea
                                        id="event_description"
                                        value={data.event_description}
                                        onChange={(e) => setData('event_description', e.target.value)}
                                        placeholder="Event description"
                                        rows={4}
                                    />
                                    {errors?.event_description && <p className="text-sm text-destructive">{errors.event_description}</p>}
                                </div>
                            </div>
                        </div>

                        <div className="flex justify-end space-x-2">
                            <Button asChild variant="outline">
                                <Link href={route('site.posts.index')}>Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                Update Post
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}