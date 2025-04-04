import { Link } from '@inertiajs/react';

export default function AppLogo() {
    return (
        <Link href="/site/settings/profile" className="flex items-center">
            <div
                className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <img
                    src="/storage/images/marketoo.png"
                    alt="Marketoo Logo"
                    className="size-5 object-contain"
                />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-none font-semibold">Marketoo</span>
            </div>
        </Link>
    );
}
