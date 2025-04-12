import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/site/posts' },
    { title: 'Create', href: '/site/posts/create' }
];

export default function Create({ auth }: { auth: any }) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        content: '',
        privacy: 'public',
        event_name: '',
        event_date_time: '',
        slug: ''
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('site.posts.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Post" />
            <div className="flex-1 p-6 lg:p-8">
                <div className="max-w-4xl mx-auto space-y-6">
                    <HeadingSmall title="Create a New Post" description="Fill in the details to create a new post." />

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <Label htmlFor="title">Title</Label>
                            <Input
                                id="title"
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                placeholder="Enter post title"
                            />
                            {errors.title && <p className="text-sm text-destructive">{errors.title}</p>}
                        </div>

                        <div>
                            <Label htmlFor="content">Content</Label>
                            <Textarea
                                id="content"
                                value={data.content}
                                onChange={(e) => setData('content', e.target.value)}
                                placeholder="Enter post content"
                            />
                            {errors.content && <p className="text-sm text-destructive">{errors.content}</p>}
                        </div>

                        <div>
                            <Label htmlFor="privacy">Privacy</Label>
                            <Select
                                value={String(data.privacy)}
                                onValueChange={(value) => setData('privacy', Number(value))}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select privacy" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="1">Public</SelectItem>
                                    <SelectItem value="2">Private</SelectItem>
                                    <SelectItem value="3">Unlisted</SelectItem>
                                </SelectContent>
                            </Select>
                            {errors.privacy && <p className="text-sm text-destructive">{errors.privacy}</p>}
                        </div>

                        <div>
                            <Label htmlFor="event_name">Event Name (Optional)</Label>
                            <Input
                                id="event_name"
                                value={data.event_name}
                                onChange={(e) => setData('event_name', e.target.value)}
                                placeholder="Enter event name"
                            />
                            {errors.event_name && <p className="text-sm text-destructive">{errors.event_name}</p>}
                        </div>

                        <div>
                            <Label htmlFor="event_date_time">Event Date & Time (Optional)</Label>
                            <Input
                                id="event_date_time"
                                type="datetime-local"
                                value={data.event_date_time}
                                onChange={(e) => setData('event_date_time', e.target.value)}
                            />
                            {errors.event_date_time &&
                                <p className="text-sm text-destructive">{errors.event_date_time}</p>}
                        </div>

                        <div>
                            <Label htmlFor="slug">Slug (Optional)</Label>
                            <Input
                                id="slug"
                                value={data.slug}
                                onChange={(e) => setData('slug', e.target.value)}
                                placeholder="Enter post slug"
                            />
                            {errors.slug && <p className="text-sm text-destructive">{errors.slug}</p>}
                        </div>

                        <div className="flex justify-end space-x-2">
                            <Button asChild variant="outline">
                                <Link href={route('site.posts.index')}>Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                Create Post
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
