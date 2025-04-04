import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle, Eye, EyeOff } from 'lucide-react';
import { FormEventHandler, useEffect, useState } from 'react';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Card, CardContent } from '@/components/ui/card';
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

// Example country data - in a real app, this would come from an API
const countries = [
    { id: '1', name: 'United States', code: '+1' },
    { id: '2', name: 'Canada', code: '+1' },
    { id: '3', name: 'United Kingdom', code: '+44' }
    // Add more countries as needed
];

// Example city data - in a real app, this would come from an API
const cities = {
    '1': [
        { id: '1', name: 'New York' },
        { id: '2', name: 'Los Angeles' },
        { id: '3', name: 'Chicago' }
    ],
    '2': [
        { id: '4', name: 'Toronto' },
        { id: '5', name: 'Vancouver' },
        { id: '6', name: 'Montreal' }
    ],
    '3': [
        { id: '7', name: 'London' },
        { id: '8', name: 'Manchester' },
        { id: '9', name: 'Birmingham' }
    ]
};

export default function Register() {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [availableCities, setAvailableCities] = useState<{ id: string; name: string }[]>([]);

    const { data, setData, post, processing, errors, reset } = useForm<RegisterForm>({
        name: '',
        email: '',
        phone: '',
        country_code: '+1',
        country_id: '',
        city_id: '',
        password: '',
        password_confirmation: '',
        is_accept_terms: false
    });

    // Update country code and available cities when country changes
    useEffect(() => {
        if (data.country_id) {
            // Set country code
            const selectedCountry = countries.find(country => country.id === data.country_id);
            if (selectedCountry) {
                setData('country_code', selectedCountry.code);
            }

            // Set available cities
            setAvailableCities(cities[data.country_id] || []);

            // Reset city selection
            setData('city_id', '');
        }
    }, [data.country_id]);

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

                <Card>
                    <CardContent className="p-4">
                        <div className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="country_id">Country</Label>
                                <Select
                                    value={data.country_id}
                                    onValueChange={(value) => setData('country_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select Country" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {countries.map(country => (
                                            <SelectItem key={country.id} value={country.id}>
                                                {country.name} ({country.code})
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.country_id} />
                            </div>

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
                                        readOnly={!!data.country_id}
                                    />
                                    <Input
                                        id="phone"
                                        type="tel"
                                        required
                                        className="w-3/4"
                                        value={data.phone}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        placeholder="123-456-7890"
                                    />
                                </div>
                                <InputError message={errors.phone} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="city_id">City</Label>
                                <Select
                                    value={data.city_id}
                                    onValueChange={(value) => setData('city_id', value)}
                                    disabled={!data.country_id}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select City" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {availableCities.map(city => (
                                            <SelectItem key={city.id} value={city.id}>
                                                {city.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.city_id} />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div className="space-y-2">
                    <Label htmlFor="password">Password</Label>
                    <div className="relative">
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            required
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="••••••••"
                        />
                        <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            className="absolute right-0 top-0 h-full px-3"
                            onClick={() => setShowPassword(!showPassword)}
                        >
                            {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                        </Button>
                    </div>
                    <p className="text-xs text-muted-foreground">
                        Password must be at least 8 characters long
                    </p>
                    <InputError message={errors.password} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                    <div className="relative">
                        <Input
                            id="password_confirmation"
                            type={showConfirmPassword ? 'text' : 'password'}
                            required
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            placeholder="••••••••"
                        />
                        <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            className="absolute right-0 top-0 h-full px-3"
                            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                        >
                            {showConfirmPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                        </Button>
                    </div>
                    <InputError message={errors.password_confirmation} />
                </div>

                <div className="flex items-center space-x-2">
                    <Checkbox
                        id="is_accept_terms"
                        checked={data.is_accept_terms}
                        onCheckedChange={(checked) => setData('is_accept_terms', !!checked)}
                    />
                    <Label htmlFor="is_accept_terms" className="text-sm">
                        I accept the <TextLink href="/terms">terms and conditions</TextLink> and <TextLink
                        href="/privacy">privacy policy</TextLink>
                    </Label>
                </div>
                <InputError message={errors.is_accept_terms} />

                <Button type="submit" className="w-full" disabled={processing}>
                    {processing ? <LoaderCircle className="mr-2 h-4 w-4 animate-spin" /> : null}
                    Create account
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
