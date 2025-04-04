import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

interface LoginForm {
    email: string;
    password: string;
    remember: boolean;
}

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm<LoginForm>({
        email: '',
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
        <AuthLayout title="Log in to your account" description="Enter your email and password below to log in">
            <Head title="Log in" />
            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <Label htmlFor="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autoFocus
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="email@example.com"
                    />
                    <InputError message={errors.email} />
                </div>

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
                    <Checkbox id="remember" name="remember" checked={data.remember}
                              onChange={() => setData('remember', !data.remember)} />
                    <Label htmlFor="remember">Remember me</Label>
                </div>

                <Button type="submit" className="w-full mt-4" disabled={processing}>
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                    Log in
                </Button>

                <div className="text-center text-sm">
                    Don't have an account?{' '}
                    <TextLink href={route('site.register')}>
                        Sign up
                    </TextLink>
                </div>
            </form>

            {status && <div className="mt-4 text-center text-sm text-green-600">{status}</div>}
        </AuthLayout>
    );
}
