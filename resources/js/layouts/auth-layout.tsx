// /layouts/auth-layout.jsx
import React from 'react';
import { Head } from '@inertiajs/react';

export default function AuthLayout({ children, title, description }) {
    return (
        <div className="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-auth-bg text-white">
            <Head title={title} />

            <div className="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 className="mt-6 text-center text-3xl font-extrabold">{title}</h2>
                {description && (
                    <p className="mt-2 text-center text-sm text-gray-400">{description}</p>
                )}
            </div>

            <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div className="bg-form-bg py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-800">
                    {children}
                </div>
            </div>
        </div>
    );
}
