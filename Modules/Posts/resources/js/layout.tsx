// Modules/Posts/Resources/js/layout.tsx
import { ReactNode } from 'react';

interface PostsLayoutProps {
    children: ReactNode;
}

export default function PostsLayout({ children }: PostsLayoutProps) {
    return (
        <div className="posts-layout container mx-auto p-4">
            {children}
        </div>
    );
}
