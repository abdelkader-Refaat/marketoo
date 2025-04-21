import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, LayoutGrid, FileText, Home } from 'lucide-react';
import AppLogo from './app-logo';


const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/site/dashboard',
        icon: Home
    },
    {
        title: 'Posts',
        url: '/site/posts',
        icon: FileText
    },
    {
        title: 'Settings',
        url: '/site/settings/profile',
        icon: LayoutGrid
    }
];

const footerNavItems: NavItem[] = [
    {
        title: 'My GitHub Repo',
        url: 'https://github.com/abdelkader-Refaat/marketoo',
        icon: BookOpen,
        external: true
    }
];


export function AppSidebar() {
    const { url } = usePage();

    return (
        <Sidebar collapsible="icon" variant="inset" className="w-64 lg:w-72">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/site/dashboard">
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems.map((item) => ({ ...item, active: url.startsWith(item.url) }))} />
            </SidebarContent>

            <SidebarFooter>
                <div className="flex flex-col gap-4">

                    <NavFooter
                        items={footerNavItems.map((item) => ({ ...item, href: item.url, external: item.external }))}
                        className="mt-auto"
                    />
                    <NavUser />
                </div>
            </SidebarFooter>
        </Sidebar>
    );
}