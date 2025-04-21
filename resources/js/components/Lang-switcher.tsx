// components/language-switcher.tsx
import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';

interface LanguageSwitcherProps {
    className?: string;
}

export function LanguageSwitcher({ className }: LanguageSwitcherProps) {
    const { props } = usePage();
    const currentLocale = props.currentLocale || 'en'; // Default to English

    return (
        <div className={`flex gap-2 ${className}`}>
            <Link
                href={route('admin.switch_lang', 'en')}
                method="get"
                className={`px-3 py-1 text-sm rounded-md ${
                    currentLocale === 'en'
                        ? 'bg-primary text-primary-foreground'
                        : 'bg-muted text-muted-foreground hover:bg-accent'
                }`}> EN </Link>
            <Link
                href={route('admin.switch_lang', 'ar')}
                method="get"
                className={`px-3 py-1 text-sm rounded-md ${
                    currentLocale === 'ar'
                        ? 'bg-primary text-primary-foreground'
                        : 'bg-muted text-muted-foreground hover:bg-accent'
                }`}> AR </Link>
        </div>
    );
}