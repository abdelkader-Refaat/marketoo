import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import AuthLayout from '@/layouts/auth-layout';

interface RegisterForm {
    name: string;
    email: string;
    phone: string;
    country_code: string;
    country_id: string;
    city_id: string;
    password: string;
    password_confirmation: string;
    is_accept_terms: boolean;
}

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm<RegisterForm>({
        name: '',
        email: '',
        phone: '',
        country_code: '',
        country_id: '',
        city_id: '',
        password: '',
        password_confirmation: '',
        is_accept_terms: false
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('site.register'), {
            onFinish: () => reset('password', 'password_confirmation')
        });
    };

    return (
        <AuthLayout title="Create an account" description="Enter your details below to create your account">
            <Head title="Register" />
            <form onSubmit={submit} className="space-y-6">
                <div className="space-y-2">
                    <Label htmlFor="name">Full Name</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        placeholder="John Doe"
                    />
                    <InputError message={errors.name} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="email@example.com"
                    />
                    <InputError message={errors.email} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="phone">Phone number</Label>
                    <div className="flex space-x-2">
                        <Input
                            id="country_code"
                            type="text"
                            required
                            value={data.country_code}
                            onChange={(e) => setData('country_code', e.target.value)}
                            placeholder="+1"
                        />
                        <Input
                            id="phone"
                            type="tel"
                            required
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            placeholder="123-456-7890"
                        />
                    </div>
                    <InputError message={errors.phone} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="country_id">Country</Label>
                    <Select
                        id="country_id"
                        value={data.country_id}
                        onValueChange={(value) => setData('country_id', value)}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select Country" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="1">United States</SelectItem>
                            <SelectItem value="2">Canada</SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError message={errors.country_id} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="city_id">City</Label>
                    <Select
                        id="city_id"
                        value={data.city_id}
                        onValueChange={(value) => setData('city_id', value)}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select City" />
                        </SelectTrigger>
                        <SelectContent>
                            {data.country_id === '1' && (
                                <>
                                    <SelectItem value="1">New York</SelectItem>
                                    <SelectItem value="2">Los Angeles</SelectItem>
                                </>
                            )}
                            {data.country_id === '2' && (
                                <>
                                    <SelectItem value="3">Toronto</SelectItem>
                                    <SelectItem value="4">Vancouver</SelectItem>
                                </>
                            )}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.city_id} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="password">Password</Label>
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

                <div className="space-y-2">
                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        placeholder="Confirm Password"
                    />
                    <InputError message={errors.password_confirmation} />
                </div>

                <div className="flex items-center">
                    <Checkbox
                        id="is_accept_terms"
                        checked={data.is_accept_terms}
                        onCheckedChange={(checked) => setData('is_accept_terms', !!checked)}
                    />
                    <Label htmlFor="is_accept_terms">
                        I accept the terms and conditions
                    </Label>
                </div>
                <InputError message={errors.is_accept_terms} />

                <Button type="submit" className="w-full" disabled={processing}>
                    {processing ? <LoaderCircle className="animate-spin" /> : 'Create account'}
                </Button>

                <div className="text-center text-sm">
                    Already have an account?{' '}
                    <TextLink href={route('site.login')}>
                        Log in
                    </TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}
