import { Head, useForm, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Eye, EyeOff, LoaderCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/input-error';
import AuthLayout from '@/layouts/auth-layout';

interface Country {
    id: string;
    name: string;
    code: string;
    cities?: City[]; // Make cities optional since we might fetch them separately
}

interface City {
    id: string;
    name: string;
}

export default function Register() {
    const [countries, setCountries] = useState<Country[]>([]);
    const [cities, setCities] = useState<City[]>([]);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [avatarPreview, setAvatarPreview] = useState<string | null>(null);
    const [isLoadingCities, setIsLoadingCities] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        country_id: '',
        city_id: '',
        country_code: '',
        phone: '',
        is_accept_terms: false,
        avatar: null
    });

    // Load countries on component mount
    useEffect(() => {
        router.get('/api/v1/countries', {}, {
            onSuccess: (data) => setCountries(data.props.countries || []),
            preserveState: true
        });
    }, []);

    // Load cities when country changes
    useEffect(() => {
        if (data.country_id) {
            setIsLoadingCities(true);
            const selectedCountry = countries.find(c => c.id === data.country_id);

            // If cities are already included with countries, use them
            if (selectedCountry?.cities) {
                setCities(selectedCountry.cities);
                setIsLoadingCities(false);
            }
            // Otherwise fetch cities separately
            else {
                router.get(`/api/v1/countries/${data.country_id}/cities`, {}, {
                    onSuccess: (data) => {
                        setCities(data.props.cities || []);
                        setIsLoadingCities(false);
                    },
                    onError: () => setIsLoadingCities(false)
                });
            }

            // Set country code
            setData('country_code', selectedCountry?.code || '');
        } else {
            setCities([]);
            setData('city_id', '');
        }
    }, [data.country_id]);

    const handleAvatarChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setData('avatar', file);
            setAvatarPreview(URL.createObjectURL(file));
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('site.register'), {
            preserveScroll: true,
            onSuccess: () => {
                // Reset form on successful registration
                setAvatarPreview(null);
            }
        });
    };

    return (
        <AuthLayout title="Create Account">
            <Head title="Register" />
            <form onSubmit={handleSubmit} className="space-y-4">
                {/* Avatar Upload */}
                <div className="flex flex-col items-center">
                    {avatarPreview ? (
                        <img src={avatarPreview} className="w-24 h-24 rounded-full mb-2" alt="Avatar" />
                    ) : (
                        <div className="w-24 h-24 rounded-full bg-gray-100 mb-2" />
                    )}
                    <Label htmlFor="avatar" className="cursor-pointer">
                        <span className="text-blue-600">Upload Avatar</span>
                        <input
                            id="avatar"
                            type="file"
                            accept="image/*"
                            className="hidden"
                            onChange={handleAvatarChange}
                        />
                    </Label>
                    <InputError message={errors.avatar} />
                </div>

                {/* Name */}
                <div>
                    <Label htmlFor="name">Full Name</Label>
                    <Input
                        id="name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />
                    <InputError message={errors.name} />
                </div>

                {/* Email */}
                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    <InputError message={errors.email} />
                </div>

                {/* Country */}
                <div>
                    <Label>Country</Label>
                    <Select
                        value={data.country_id}
                        onValueChange={(value) => setData('country_id', value)}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select country" />
                        </SelectTrigger>
                        <SelectContent>
                            {countries.map(country => (
                                <SelectItem key={country.id} value={country.id}>
                                    {country.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.country_id} />
                </div>

                {/* City */}
                <div>
                    <Label>City</Label>
                    <Select
                        value={data.city_id}
                        onValueChange={(value) => setData('city_id', value)}
                        disabled={!data.country_id || isLoadingCities}
                    >
                        <SelectTrigger>
                            {isLoadingCities ? (
                                <span className="text-muted-foreground">Loading cities...</span>
                            ) : (
                                <SelectValue placeholder={data.country_id ? 'Select city' : 'Select country first'} />
                            )}
                        </SelectTrigger>
                        <SelectContent>
                            {cities.map(city => (
                                <SelectItem key={city.id} value={city.id}>
                                    {city.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.city_id} />
                </div>

                {/* Phone */}
                <div>
                    <Label>Phone Number</Label>
                    <div className="flex gap-2">
                        <Input
                            value={data.country_code}
                            onChange={(e) => setData('country_code', e.target.value)}
                            className="w-1/4"
                            placeholder="+1"
                            readOnly={!!data.country_id}
                        />
                        <Input
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            className="w-3/4"
                            placeholder="Phone number"
                        />
                    </div>
                    <InputError message={errors.phone} />
                </div>

                {/* Password */}
                <div>
                    <Label htmlFor="password">Password</Label>
                    <div className="relative">
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />
                        <button
                            type="button"
                            className="absolute right-2 top-2"
                            onClick={() => setShowPassword(!showPassword)}
                        >
                            {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                        </button>
                    </div>
                    <InputError message={errors.password} />
                </div>

                {/* Confirm Password */}
                <div>
                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                    <div className="relative">
                        <Input
                            id="password_confirmation"
                            type={showConfirmPassword ? 'text' : 'password'}
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            required
                        />
                        <button
                            type="button"
                            className="absolute right-2 top-2"
                            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                        >
                            {showConfirmPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                        </button>
                    </div>
                    <InputError message={errors.password_confirmation} />
                </div>

                {/* Terms Checkbox */}
                <div className="flex items-center space-x-2">
                    <Checkbox
                        id="terms"
                        checked={data.is_accept_terms}
                        onCheckedChange={(checked) => setData('is_accept_terms', !!checked)}
                    />
                    <Label htmlFor="terms">I accept the terms and conditions</Label>
                </div>
                <InputError message={errors.is_accept_terms} />

                {/* Submit Button */}
                <Button type="submit" disabled={processing} className="w-full">
                    {processing && <LoaderCircle className="animate-spin mr-2" />}
                    Register
                </Button>

                {/* Login Link */}
                <div className="text-center">
                    Already have an account?{' '}
                    <a href={route('site.login')} className="text-blue-600 hover:underline">
                        Login
                    </a>
                </div>
            </form>
        </AuthLayout>
    );
}
