import { Head, useForm, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Eye, EyeOff, LoaderCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/input-error';
import AuthLayout from '@/layouts/auth-layout';

interface Country {
    id: number;
    name: string;
    key: string; // country code
    cities: City[];
}

interface City {
    id: number;
    name: string;
    country: {
        id: number;
        name: string;
    };
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
        const fetchCountries = async () => {
            try {
                const response = await fetch('/api/v1/countries');
                const result = await response.json();
                if (result.key === 'success') {
                    setCountries(result.data);
                }
            } catch (error) {
                console.error('Failed to fetch countries:', error);
            }
        };

        fetchCountries();
    }, []);

    // Load cities when country changes
    useEffect(() => {
        const fetchCities = async () => {
            if (!data.country_id) {
                setCities([]);
                return;
            }

            setIsLoadingCities(true);
            try {
                // Check if cities are already loaded with countries
                const selectedCountry = countries.find(c => c.id.toString() === data.country_id);
                if (selectedCountry?.cities?.length) {
                    setCities(selectedCountry.cities);
                    setIsLoadingCities(false);
                    return;
                }

                // Otherwise fetch cities separately
                const response = await fetch(`/api/v1/countries/${data.country_id}/cities`);
                const result = await response.json();
                if (result.key === 'success') {
                    setCities(result.data);
                }
            } catch (error) {
                console.error('Failed to fetch cities:', error);
            } finally {
                setIsLoadingCities(false);
            }
        };

        fetchCities();

        // Set country code when country changes
        if (data.country_id) {
            const selectedCountry = countries.find(c => c.id.toString() === data.country_id);
            if (selectedCountry) {
                setData('country_code', selectedCountry.key);
            }
        }
    }, [data.country_id, countries]);

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
                        onValueChange={(value) => {
                            setData('country_id', value);
                            setData('city_id', ''); // Reset city when country changes
                        }}
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select country" />
                        </SelectTrigger>
                        <SelectContent>
                            {countries.map(country => (
                                <SelectItem key={country.id} value={country.id.toString()}>
                                    {country.name} (+{country.key})
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
                            ) : data.country_id ? (
                                <SelectValue placeholder="Select city" />
                            ) : (
                                <SelectValue placeholder="Select country first" />
                            )}
                        </SelectTrigger>
                        <SelectContent>
                            {cities.map(city => (
                                <SelectItem key={city.id} value={city.id.toString()}>
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
                            placeholder="+966"
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
