import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';

interface RegisterForm {
    name: string;
    email: string;
    phone: string;
    country_code: string;
    country_id: string;
    city_id: string;
    password: string;
    password_confirmation: string;
    avatar: File | null;
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
        avatar: null,
        is_accept_terms: false
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        const formData = new FormData();
        Object.entries(data).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        });

        post(route('site.register'), {
            data: formData,
            onFinish: () => reset('password', 'password_confirmation')
        });
    };

    return (
        <AuthLayout title="Create an account" description="Enter your details below to create your account">
            <Head title="Register" />
            <form
                className="w-full max-w-md mx-auto bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg"
                onSubmit={submit}
                encType="multipart/form-data"
            >
                <div className="space-y-6">
                    {/* Name Field */}
                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300" htmlFor="name">
                            Full Name
                        </Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            maxLength={50}
                            autoFocus
                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            disabled={processing}
                            placeholder="John Doe"
                        />
                        <InputError message={errors.name} className="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {/* Email Field */}
                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300" htmlFor="email">
                            Email Address
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            disabled={processing}
                            placeholder="email@example.com"
                        />
                        <InputError message={errors.email} className="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {/* Phone Field */}
                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Phone Number
                        </Label>
                        <div className="flex gap-3">
                            <div className="w-24">
                                <Input
                                    id="country_code"
                                    type="text"
                                    required
                                    className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    value={data.country_code}
                                    onChange={(e) => setData('country_code', e.target.value)}
                                    disabled={processing}
                                    placeholder="1"
                                />
                                <InputError message={errors.country_code}
                                            className="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>
                            <div className="flex-1">
                                <Input
                                    id="phone"
                                    type="tel"
                                    required
                                    className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    disabled={processing}
                                    placeholder="1234567890"
                                />
                                <InputError message={errors.phone}
                                            className="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>
                        </div>
                    </div>

                    {/* Country and City Selection */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="space-y-2">
                            <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                   htmlFor="country_id">
                                Country
                            </Label>
                            <Select
                                required
                                value={data.country_id}
                                onValueChange={(value) => setData('country_id', value)}
                                disabled={processing}
                            >
                                <SelectTrigger
                                    className="w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                    <SelectValue placeholder="Select country" />
                                </SelectTrigger>
                                <SelectContent
                                    className="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg">
                                    <SelectItem value="1">United States</SelectItem>
                                    <SelectItem value="2">Canada</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError message={errors.country_id}
                                        className="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <div className="space-y-2">
                            <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                   htmlFor="city_id">
                                City
                            </Label>
                            <Select
                                required
                                value={data.city_id}
                                onValueChange={(value) => setData('city_id', value)}
                                disabled={processing || !data.country_id}
                            >
                                <SelectTrigger
                                    className="w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                    <SelectValue placeholder="Select city" />
                                </SelectTrigger>
                                <SelectContent
                                    className="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg">
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
                            <InputError message={errors.city_id}
                                        className="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    {/* Password Fields */}
                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300"
                               htmlFor="password">
                            Password
                        </Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            minLength={6}
                            maxLength={50}
                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            disabled={processing}
                            placeholder="Password"
                        />
                        <InputError message={errors.password} className="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300"
                               htmlFor="password_confirmation">
                            Confirm Password
                        </Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            minLength={6}
                            maxLength={50}
                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            disabled={processing}
                            placeholder="Confirm password"
                        />
                        <InputError message={errors.password_confirmation}
                                    className="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {/* Avatar Upload */}
                    <div className="space-y-2">
                        <Label className="block text-sm font-medium text-gray-700 dark:text-gray-300" htmlFor="avatar">
                            Profile Picture (Optional)
                        </Label>
                        <div className="flex items-center gap-4">
                            <label className="block">
                                <span className="sr-only">Choose profile photo</span>
                                <input
                                    id="avatar"
                                    type="file"
                                    accept="image/*"
                                    className="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    dark:file:bg-blue-900 dark:file:text-blue-100
                                    dark:hover:file:bg-blue-800"
                                    onChange={(e) => setData('avatar', e.target.files?.[0] || null)}
                                    disabled={processing}
                                />
                            </label>
                        </div>
                        <InputError message={errors.avatar} className="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {/* Terms Acceptance */}
                    <div className="flex items-start">
                        <div className="flex items-center h-5">
                            <Checkbox
                                id="terms"
                                required
                                checked={data.is_accept_terms}
                                onCheckedChange={(checked) => setData('is_accept_terms', !!checked)}
                                disabled={processing}
                                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                            />
                        </div>
                        <div className="ml-3 text-sm">
                            <Label htmlFor="terms" className="font-medium text-gray-700 dark:text-gray-300">
                                I accept the <a href="#"
                                                className="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">terms
                                and conditions</a>
                            </Label>
                            <InputError message={errors.is_accept_terms}
                                        className="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    {/* Submit Button */}
                    <Button
                        type="submit"
                        className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800"
                        disabled={processing}
                    >
                        {processing ? (
                            <>
                                <LoaderCircle className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" />
                                Processing...
                            </>
                        ) : 'Create account'}
                    </Button>
                </div>

                <div className="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?{' '}
                    <TextLink
                        href={route('site.login')}
                        className="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        Log in
                    </TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}
