import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle, Mail, Phone } from 'lucide-react';
import { FormEventHandler, useState } from 'react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AuthLayout from '@/layouts/auth-layout';

interface LoginForm {
    identifier: string; // Can be email or phone
    login_type: 'email' | 'phone';
    country_code: string;
    password: string;
    remember: boolean;
}

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm<LoginForm>({
        identifier: '',
        login_type: 'email',
        country_code: '+1', // Default country code
        password: '',
        remember: false
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('site.login'), {
            onFinish: () => reset('password')
        });
    };

    return (
        <AuthLayout title="Log in to your account" description="Enter your credentials below to log in">
            <Head title="Log in" />
            <Tabs value={data.login_type} onValueChange={(value) => setData('login_type', value as 'email' | 'phone')}
                  className="w-full">
                <TabsList className="grid w-full grid-cols-2 mb-6 custom-tabs-list">
                    <TabsTrigger value="email" className="flex items-center gap-2 custom-tabs-trigger">
                        <Mail size={16} />
                        <span>Email</span>
                    </TabsTrigger>
                    <TabsTrigger value="phone" className="flex items-center gap-2 custom-tabs-trigger">
                        <Phone size={16} />
                        <span>Phone</span>
                    </TabsTrigger>
                </TabsList>

                <form onSubmit={submit} className="space-y-6">
                    <TabsContent value="email" className="space-y-6">
                        <div className="space-y-2">
                            <Label htmlFor="email">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                required
                                autoFocus
                                value={data.identifier}
                                onChange={(e) => setData('identifier', e.target.value)}
                                placeholder="email@example.com"
                            />
                            <InputError message={errors.identifier} />
                        </div>
                    </TabsContent>

                    <TabsContent value="phone" className="space-y-6">
                        <div className="space-y-2">
                            <Label htmlFor="phone">Phone number</Label>
                            <div className="flex space-x-2">
                                <Input
                                    id="country_code"
                                    type="text"
                                    required
                                    className="w-1/4"
                                    value={data.country_code}
                                    onChange={(e) => setData('country_code', e.target.value)}
                                    placeholder="+1"
                                />
                                <Input
                                    id="phone"
                                    type="tel"
                                    required
                                    className="w-3/4"
                                    value={data.identifier}
                                    onChange={(e) => setData('identifier', e.target.value)}
                                    placeholder="123-456-7890"
                                />
                            </div>
                            <InputError message={errors.identifier} />
                        </div>
                    </TabsContent>

                    <div className="space-y-2">
                        <div className="flex justify-between">
                            <Label htmlFor="password">Password</Label>
                            {canResetPassword && (
                                <TextLink href={route('site.password.request')} className="text-sm">
                                    Forgot password?
                                </TextLink>
                            )}
                        </div>
                        <Input
                            id="password"
                            type="password"
                            required
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="Password"
                        />
                        <InputError message={errors.password} />
                    </div>

                    <div className="flex items-center space-x-3">
                        <Checkbox
                            id="remember"
                            name="remember"
                            checked={data.remember}
                            onCheckedChange={(checked) => setData('remember', !!checked)}
                        />
                        <Label htmlFor="remember">Remember me</Label>
                    </div>

                    <Button type="submit" className="w-full" disabled={processing}>
                        {processing ? <LoaderCircle className="mr-2 h-4 w-4 animate-spin" /> : null}
                        Log in
                    </Button>

                    <div className="text-center text-sm">
                        Don't have an account?{' '}
                        <TextLink href={route('site.register')}>
                            Sign up
                        </TextLink>
                    </div>
                </form>
            </Tabs>

            {status && <div className="mt-4 text-center text-sm text-green-600">{status}</div>}
        </AuthLayout>
    );
}
